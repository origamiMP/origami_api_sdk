# Tests — Repositories and REST clients

A repository test checks both sides of the call, which are both part of the public contract of the SDK: **the HTTP request sent** and **the DTO built** from the response.

## Fake HTTP client (`TestsRestClient`)

No network and no mocking library (`createMock()`, Mockery): a real REST client of the SDK answers with queued Guzzle responses, so all its logic (headers, `v1/` prefix, errors) really runs.

| Helper | Role |
|---|---|
| `mockDataRestClientReturning(...)`, `mockGuestRestClientReturning(...)`, `mockOauthRestClientReturning(...)` | Client matching the repository (`Data`, `Guest`, `Oauth`) |
| `jsonResponse($body, $status = 200)`, `emptyResponse($status = 204)` | JSON response, response without body |
| `new Response($status, [], $rawBody)` | Raw body (HTML, invalid JSON, `{}`…) |
| a `\Throwable` instead of a response | Network failure |
| `useMockedGuzzleClient($restClient, [...])` | Client built by hand (custom user agent, user group context) |

## What to check, in this order

1. The request: `assertLastApiRequestWas('GET', 'sellers/42')` (path without `v1/`; `prefix: ''` for Oauth).
2. What it carries: `assertLastApiRequestQueryWas()` (values are always strings: `'page' => '2'`, `true` → `'1'`), `assertLastApiRequestJsonWas()`, or `assertLastApiRequestHadNoBody()`.
3. The DTO: `assertInstanceOf()`, then its id, then the values the case involves.

The expected JSON is written with snake_case keys and **literal values**, as sent to the API, not the SDK enums or dates (`OrigamiSellerDataApiRepositoryTest::test_create_case_1`). An unset param bag property is not sent: a case relying on it says so in its docblock.

## Expected coverage

- At least one test per public method.
- One more case per notable variation: filter not available (not sent), optional parameter not set, method called without its optional param bag, empty or particular response (`[]`, no `data` key), param bag defaults versus values set.
- One include test per method accepting includes (see below).
- Not retested in each repository: what the REST client and `RestApiRepository` tests cover (HTTP errors, unreadable response).

### Include tests

A method accepts includes when its param bag uses `HasIncludes` and the DTO of its `getRequestMainDto()` declares at least one include that is not commented. The include rules themselves are in `references/code/param-bags.md`: if the tested method does not follow them, tell the user rather than writing a test that freezes the gap.

The include test (`OrigamiUserGroupInvitationDataApiRepositoryTest::test_get_history_case_2`):
1. requests the available includes plus an unknown or commented one with `setIncludes()`;
2. mocks a response holding the include, wrapped as Fractal does (`['data' => …]`);
3. checks that the `include` query only holds the available includes;
4. checks that the include is read: type of the included DTO and its id.

The filtering itself is tested once in `Traits/ParamBags/HasIncludesTest`. When an include is added or uncommented in a DTO, add the include test of every method returning this DTO.

## Reference tests per endpoint type

| Endpoint | Test |
|---|---|
| Paginated list with filters, pagination, search | `OrigamiSellerDataApiRepositoryTest::test_list_case_1` |
| Non-paginated list | `OrigamiModuleDataApiRepositoryTest::test_list_case_1` |
| Method called without its optional param bag | `OrigamiSellerDataApiRepositoryTest::test_list_case_4` |
| Reading an item from the `data` key | `OrigamiSellerDataApiRepositoryTest::test_get_case_1` |
| Creation (JSON body and response) | `OrigamiTaxDataApiRepositoryTest::test_create_case_1` |
| Partial update (204) | `OrigamiTaxDataApiRepositoryTest::test_update_case_1` |
| Action without body (204) | `OrigamiTaxDataApiRepositoryTest::test_delete_case_1` |

## REST client tests

The behavior shared by every client (headers, URL prefix, HTTP verbs, API errors turned into exceptions) is tested in `OrigamiRestClientTest`, through the Guest client; what is specific to one client is tested in its own file (`OrigamiDataRestClientTest`). These tests call `$restClient->get('some_resource')` directly and read the request with `$this->lastApiRequest()`. API error responses are built with `mockApiErrorsResponse()` and `mockApiErrorData()` (`TestsErrorDtos`).
