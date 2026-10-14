# Tests — Writing a test

## Method naming

`test_<subject>_case_<n>`, in snake_case:

- `<subject>` is **the tested method**, in snake_case (`getLegalTypes()` → `test_get_legal_types_case_1`), or, for a cross-cutting behavior, **the name of this behavior** (`test_headers_case_1`, `test_url_case_2`, `test_request_error_case_3`, `test_state_case_1`, `test_roles_case_2`).
- When the tested effect can only be observed through another method, the subject is **the name of this effect**, not of the method called. For instance, sending includes as query is observed through `asGuzzleParams()`: the test is named `test_query_case_1`, not `test_as_guzzle_params_case_1`.
- `<n>` starts at 1 for each subject and has no gaps. The number carries no meaning: the docblock describes the case.

Neither the `#[Test]` attribute, nor the `@test` annotation, nor descriptive camelCase names are used.

## Docblock

Each test has an **English** docblock, one to three lines long, describing the **expected behavior** (not the steps of the test), with « should »:

```php
/**
 * `GET sellers` should send the filters, pagination and search params as query, and read the paginated list
 * answered.
 */
```

- For an endpoint, it starts with the verb and the path between backticks: `` `POST forms/{id}/duplicate` ``, `` `PATCH taxes/{id}` ``.
- It mentions the specifics justifying the case: « A filter not available on `GET sellers` should not be sent. », « (no `v1/` prefix) », « answers a flat object (no `data` key) ».
- It can be left out when the name and the body are enough, typically for a call without body nor response (`test_delete_case_1`, `test_activate_case_1`) or for the trivial variant of a case already described (`test_collect_included_case_2`: the empty list).
- Lines stop around 120 characters.

The **class** only has a docblock if it tests through an intermediary or guards a convention:

```php
/**
 * Behavior shared by every Origami rest client, tested through the Guest one (which adds nothing to it).
 */
```

## Test body

Three blocks separated by a blank line: arrange, act, assert.

```php
public function test_get_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerApiData(['id' => 42])]));

    $seller = (new OrigamiSellerDataApiRepository($restClient))->get(42);

    $this->assertLastApiRequestWas('GET', 'sellers/42');
    $this->assertInstanceOf(SellerDto::class, $seller);
    $this->assertSame(42, $seller->id);
}
```

- **Arrange**: the client first (`$restClient = …`), then the param bags. A param bag is filled property by property (`$paramBag->email = '…';`), in the order the API expects them. For a tree of param bags, build the leaves first and the root last.
- **Act**: the tested class is instantiated on the same line as the call, and the result is stored in a variable named after what it holds (`$seller`, `$forms`, `$invitation`, `$legalTypes`). `$result` is kept for generic DTOs (success, message). No variable if the method returns nothing.
- **Assert**: the request first, then the response (see `repositories.md`).
- A blank line can separate groups of assertions when going down into a nested object; the node is then extracted into a variable (`$page = $form->pages->first();`).

When there is no separate action, the test only has **two blocks**: building, then assertions. This is the case when building is itself the tested action (a DTO built from a response, see `dtos-and-shared-behavior.md`), or when checking the default state of a new object:

```php
public function test_get_includes_case_1(): void
{
    $paramBag = new IncludesParamBagFixture;

    $this->assertSame([], $paramBag->getIncludes());
}
```

## Assertions

- **`assertSame` by default**, for scalars, arrays and enums: `$this->assertSame(FormFieldDtoTypeEnum::SELECT, $field->type);`.
- **`assertEquals`** only to compare objects whose identity does not matter (decoded `stdClass`: `assertEquals((object) [], $responseContent)`).
- `assertTrue` / `assertFalse` / `assertNull` for booleans and `null`.
- `assertInstanceOf` for the type of the returned DTO, `assertContainsOnlyInstancesOf` for the content of a collection.
- **For a list**, compare a projection rather than each item: `$this->assertSame([1, 2], $sellers->data->pluck('id')->all());`. It checks at once the count, the order and the type of each item read.
- An undefined property (missing include) is checked with `$this->assertFalse(isset($userDto->roles));`.

## Exceptions

Always `assertThatExceptionWasThrown()` (`TestsExceptions` trait). It checks that the exception is thrown, its class and, if given, its exact message. It returns the exception for further assertions:

```php
$exception = $this->assertThatExceptionWasThrown(
    fn () => $this->repository->decodeResponse(new Response(200, [], $body)),
    OrigamiApiUnknownException::class,
    'Unreadable response from Origami API : HTTP 200, the body is not valid JSON',
);

$this->assertInstanceOf(\JsonException::class, $exception->getPrevious());
```

When it fits on one line: `$this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), HttpClientException::class);`.

Use neither `expectException()` (it stops the test when the exception is thrown, nothing can be checked afterwards) nor a hand-written `try` / `$this->fail()` / `catch`.

## Data providers

When the same behavior must be checked for several inputs:

```php
#[DataProvider('invalidJsonBodyProvider')]
public function test_decode_response_case_3(string $body): void
{
    // ...
}

public static function invalidJsonBodyProvider(): array
{
    return [
        'html page'  => ['<html>Bad Gateway</html>'],
        'empty body' => [''],
        'truncated'  => ['{"data": {"id": 4'],
    ];
}
```

- The `#[DataProvider('…')]` attribute (PHPUnit 12), never the `@dataProvider` annotation.
- A `public static` method, named `<whatVaries>Provider`.
- **Readable English keys** (`'bad request'`, `'empty object'`): PHPUnit displays them when a case fails.
- Placed **right after the test** using it, or **at the end of the class** if several tests share it.
- If a parameter is a class, type it in the docblock: `@param  class-string<OrigamiApiSingleException>  $expectedExceptionClass`.

## Values used

Tests reuse the same values, so that test data is recognized at once:

| Need | Value |
|---|---|
| Id checked by the test | `42` (then `43`, or `1`, `2`, `3` for a list) |
| Id not checked | `TestCase::unique()` (in the data traits) |
| Email | `john@doe.com`, `jane@doe.com` |
| Person | `John` / `Doe` |
| Token, secret | `some-token`, `some-access-token`, `some-refresh-token` |
| Generic resource (client tests) | `some_resource` |
| API URL | `http://api.test` (`TEST_API_BASE_URI` constant) |
| External URL | `https://example.com/...` |
| Date | `'2024-05-12 16:12:56'` (timestamps of the mock data) |

Business values stay realistic (`'10 rue de Rivoli'`, `'FR7630006000011234567890189'`, `'Quote request'`): they also serve as documentation.
