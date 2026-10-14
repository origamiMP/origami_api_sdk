# Tests — Repositories and REST clients

A repository test checks both sides of the call: **the HTTP request the SDK sends** (verb, path, query, body) and **the DTO it builds** from the response. Both are part of the public contract of the SDK: a mistake on either side breaks the applications using it.

## The fake HTTP client (`TestsRestClient`)

No test calls the network and none uses `createMock()` or Mockery. A **real** REST client of the SDK is used, whose Guzzle client answers with queued responses. All the client logic (headers, `v1/` prefix, error handling) therefore really runs.

| Helper | Role |
|---|---|
| `mockDataRestClientReturning(...$responses)` | Authenticated client (`Data` repositories) |
| `mockGuestRestClientReturning(...$responses)` | Public client (`Guest` repositories) |
| `mockOauthRestClientReturning(...$responses)` | Oauth client (no `v1/` prefix) |
| `jsonResponse(array $body, int $status = 200)` | JSON response |
| `emptyResponse(int $status = 204)` | Response without body (actions returning nothing) |
| `new Response($status, [], $rawBody)` | Raw body (HTML, invalid JSON, `{}`…) |
| a `\Throwable` instead of a response | Network failure (e.g. `ConnectException`) |
| `useMockedGuzzleClient($restClient, [$responses])` | For a client built by hand (custom user agent, user group context…) |

Pick the client matching the repository: a `Data` repository gets `mockDataRestClientReturning()`, a `Guest` repository `mockGuestRestClientReturning()`.

## What to check, in this order

1. **The request**: `assertLastApiRequestWas('GET', 'sellers/42')`. The path is written without `v1/`; for Oauth, pass `prefix: ''`.
2. **What it carries**:
   - `assertLastApiRequestQueryWas([...])` for the query. Its values are **always strings** (`'page' => '2'`, a `true` boolean becomes `'1'`).
   - `assertLastApiRequestJsonWas([...])` for the JSON body.
   - `assertLastApiRequestHadNoBody()` when the request must send nothing (`delete`, `activate`, `duplicate`…).
3. **The DTO**: `assertInstanceOf(XxxDto::class, $result)`.
4. **Its values**: at least the id (or the key identifying the object), then the values the case involves (enums, dates, nested objects…).

### The expected body is written with the actual API values

The expected JSON documents the format sent to the API: write the keys in snake_case and **literal values**, not the SDK enums or objects.

```php
$paramBag->sellerType = CreateSellerParamBagTypeEnum::MARKETPLACE;
$paramBag->holidayDepartureDate = Carbon::parse('2024-07-01 08:00:00');
// ...
$this->assertLastApiRequestJsonWas([
    'seller_type'            => 'marketplace',
    'holiday_departure_date' => '2024-07-01',
    // ...
]);
```

An unset param bag property is not sent. If a case tests it (partial update, node without `id` to create), say so in the docblock and check that the key is missing from the expected JSON.

## Expected coverage

- **At least one test per public method** of the repository.
- **One more case per notable variation**, for instance:
  - a filter not available on the endpoint, which must not be sent;
  - an unset optional parameter (no `with_count` → `fieldsCount` is `null`);
  - an empty response or a particular response shape (`[]` for an empty map, object without `data` key);
  - the param bag defaults versus the values set (Oauth client id and secret).
- **One include test per method accepting some** (see below).
- No need to retest in each repository what the tests of the client or of `RestApiRepository` already cover (HTTP error codes, unreadable response).

### Includes: check every method accepting some

Includes are easily forgotten, because the first test of a method does not use them. Yet they link three pieces that can break separately: the param bag (filtering and sending the includes), the returned DTO (declaring the available ones) and its reading of the response.

**When does a method accept some?** Its param bag uses the `HasIncludes` trait, **and** the DTO returned by its `getRequestMainDto()` declares at least one include in `getAvailableIncludes()`. If this list is empty (or fully commented), `setIncludes()` drops everything: there is nothing to test until an include is added.

**Also check the other way around**: according to `references/code/param-bags.md`, every API request offering an include must be able to receive it from the SDK. If the API offers includes for the tested endpoint (transformer or controller) but the method takes no param bag with `HasIncludes`, or the DTO does not declare one of these includes (even as a comment), the code is not compliant: tell the user rather than writing a test that freezes the gap.

**The test adds a case to the method** (`test_<method>_case_<n>`), which:
1. requests the available includes **plus an unknown include** with `setIncludes()`;
2. mocks a response containing the include, wrapped as Fractal does (`['data' => …]`);
3. checks that the `include` query only holds the available includes;
4. checks that the include is read: type of the included DTO and its id.

```php
/**
 * `GET users/groups/invitations/history` should send the includes available on an invitation as query, and read
 * the included user group of each invitation.
 */
public function test_get_history_case_2(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
        $this->mockUserGroupInvitationApiData(['user_group' => ['data' => $this->mockUserGroupApiData(['id' => 42])]]),
    ]]));
    $paramBag = new GetUserGroupInvitationHistoryRequestParamBag;
    $paramBag->email = 'john@doe.com';
    $paramBag->setIncludes(['user_group', 'unknown']);

    $history = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->getHistory($paramBag);

    $this->assertLastApiRequestWas('GET', 'users/groups/invitations/history');
    $this->assertLastApiRequestQueryWas(['email' => 'john@doe.com', 'include' => ['user_group']]);
    $this->assertInstanceOf(UserGroupInvitationHistoryDto::class, $history);
    $this->assertInstanceOf(UserGroupDto::class, $history->data->first()->userGroup);
    $this->assertSame(42, $history->data->first()->userGroup->id);
}
```

The filtering itself (duplicates, nested includes, DTO without include) is tested once and for all in `Traits/ParamBags/HasIncludesTest`. The repository test only checks that the method is linked to the right DTO.

When an include is added (or uncommented) in the `getAvailableIncludes()` of a DTO, add the include test of every method returning this DTO.

## Examples per endpoint type

### Paginated list with filters

```php
/**
 * `GET sellers` should send the filters, pagination and search params as query, and read the paginated list
 * answered.
 */
public function test_list_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
        $this->mockSellerApiData(['id' => 1]),
        $this->mockSellerApiData(['id' => 2]),
    ])));
    $paramBag = new ListSellerRequestParamBag;
    $paramBag->withFilters(['address_city' => 'Paris']);
    $paramBag->page(3);
    $paramBag->search('john');

    $sellers = (new OrigamiSellerDataApiRepository($restClient))->list($paramBag);

    $this->assertLastApiRequestWas('GET', 'sellers');
    $this->assertLastApiRequestQueryWas([
        'filter'        => ['address_city' => 'Paris'],
        'page'          => '3',
        'item_per_page' => '20',
        'search'        => 'john',
    ]);
    $this->assertInstanceOf(SellerListDto::class, $sellers);
    $this->assertSame([1, 2], $sellers->data->pluck('id')->all());
}
```

A non-paginated list is mocked with `['data' => [...]]`, and the docblock says so: « answers a non-paginated list: each of its items should be read as a module ».

### Reading an item (`data` key)

See the `test_get_case_1` example in `writing-tests.md`. The docblock specifies « read … from the `data` key of the response ».

### Creation (JSON body + response)

```php
/**
 * `POST taxes` should send the tax as JSON, its translations included, and read the created one from the
 * `data` key of the response.
 */
public function test_create_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockTaxApiData([
        'id'    => 42,
        'value' => 5.5,
    ])]));
    $translation = new TaxTranslationParamBag;
    $translation->languageId = 1;
    $translation->name = 'Reduced VAT';
    $paramBag = new CreateTaxRequestParamBag;
    $paramBag->type = TaxDtoTypeEnum::PERCENTAGE;
    $paramBag->value = 5.5;
    $paramBag->isDefault = false;
    $paramBag->translations = [$translation];

    $tax = (new OrigamiTaxDataApiRepository($restClient))->create($paramBag);

    $this->assertLastApiRequestWas('POST', 'taxes');
    $this->assertLastApiRequestJsonWas([
        'type'         => 'percentage',
        'value'        => 5.5,
        'is_default'   => false,
        'translations' => [
            ['language_id' => 1, 'name' => 'Reduced VAT'],
        ],
    ]);
    $this->assertInstanceOf(TaxDto::class, $tax);
    $this->assertSame(42, $tax->id);
    $this->assertSame(5.5, $tax->value);
}
```

For a large tree of param bags, a blank line separates the building of each node.

### Update or action without response (204)

```php
/**
 * `PATCH taxes/{id}` should send only the properties set on the param bag as JSON.
 */
public function test_update_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->emptyResponse());
    $paramBag = new UpdateTaxRequestParamBag;
    $paramBag->value = 10.0;

    (new OrigamiTaxDataApiRepository($restClient))->update(42, $paramBag);

    $this->assertLastApiRequestWas('PATCH', 'taxes/42');
    $this->assertLastApiRequestJsonWas(['value' => 10.0]);
}

public function test_delete_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

    (new OrigamiTaxDataApiRepository($restClient))->delete(42);

    $this->assertLastApiRequestWas('DELETE', 'taxes/42');
    $this->assertLastApiRequestHadNoBody();
}
```

## REST client tests

The behavior shared by every client (headers, URL prefix, errors turned into exceptions) is tested in `OrigamiRestClientTest`, through the Guest client. Tests specific to one client (`Authorization` and `context` headers of the Data client, no prefix for the Oauth client) are in the file of this client. They directly call `$restClient->get('some_resource')`, and read the request with `$this->lastApiRequest()`:

```php
$this->assertSame('Bearer some-access-token', $this->lastApiRequest()->getHeaderLine('Authorization'));
```

API error responses are built with `TestsErrorDtos`:

```php
$restClient = $this->mockGuestRestClientReturning($this->mockApiErrorsResponse(
    422,
    $this->mockApiErrorData(422, 'Email is invalid.', '999999', 'email'),
    $this->mockApiErrorData(422, 'Name is required.', '999999', 'name'),
));
```
