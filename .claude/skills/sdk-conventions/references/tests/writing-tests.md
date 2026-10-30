# Tests — Writing a test

## Method naming

`test_<subject>_case_<n>`, snake_case, `<n>` starting at 1 for each subject, without gaps. The subject is:

- the tested method (`getLegalTypes()` → `test_get_legal_types_case_1`);
- or a cross-cutting behavior (`test_headers_case_1`, `test_request_error_case_3`, `test_roles_case_2`);
- or the effect tested, when it is only observed through another method (includes sent as query, observed through `asGuzzleParams()` → `test_query_case_1`).

No `#[Test]` attribute, `@test` annotation or camelCase names.

## Docblock

One to three English lines describing the **expected behavior** with « should », not the steps. For an endpoint, it starts with `` `VERB path` ``, and it mentions what justifies the case:

```php
/**
 * A filter not available on `GET sellers` should not be sent.
 */
```

It can be left out when the name and the body say everything (`test_delete_case_1`, the empty-list variant of a case). The class only has a docblock when it tests through an intermediary or guards a convention (see `dtos-and-shared-behavior.md`).

## Test body

Three blocks separated by a blank line: arrange, act, assert (`OrigamiSellerDataApiRepositoryTest::test_get_case_1`).

- **Arrange**: the client repository first, then the param bags, filled property by property; for a tree of param bags, the leaves first.
- **Act**: the tested class is instantiated on the call line; the result goes in a variable named after its content (`$seller`, `$forms`), `$result` being kept for generic DTOs.
- **Assert**: in the order given in `api-repositories.md`. A blank line can separate assertion groups when going down into a nested object, extracted into a variable (`$page = $form->pages->first();`).

Only two blocks (build, assert) when building is the tested action, or when checking the default state of a new object (`Traits/ParamBags/HasIncludesTest::test_get_includes_case_1`).

## Assertions

- `assertSame` by default (scalars, arrays, enums); `assertEquals` only for objects whose identity does not matter (decoded `stdClass`).
- A list is compared through a projection: `$this->assertSame([1, 2], $sellers->data->pluck('id')->all());`.
- `assertContainsOnlyInstancesOf` for the content of a collection; `assertFalse(isset($dto->roles))` for an undefined property.

## Exceptions

Always `assertThatExceptionWasThrown()` (`TestsExceptions`): it checks the class and, if given, a part of the message (the whole message, or its stable part when the end varies), and returns the exception for further assertions (`RestApiRepositoryTest::test_decode_response_case_3`).

```php
$this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), HttpClientException::class);
```

Never `expectException()` (nothing can be checked after the exception) nor a hand-written `try` / `fail` / `catch`.

## Data providers

`#[DataProvider('…')]` attribute (never `@dataProvider`), on a `public static <whatVaries>Provider()` method with readable English keys (PHPUnit displays them on failure). Placed right after the test using it, or at the end of the class when several tests share it (`RestApiRepositoryTest::invalidJsonBodyProvider`). A class-string parameter is typed in the docblock (`@param  class-string<…>`).

## Usual values

| Need | Value |
|---|---|
| Id checked by the test | `42` (`43`… or `1`, `2` for a list) |
| Id not checked | `TestCase::unique()` (see `mock-data.md`) |
| Email, person | `john@doe.com`, `jane@doe.com`, `John` / `Doe` |
| Token | `some-token`, `some-access-token` |
| Generic resource (client repository tests) | `some_resource` |
| URLs | `http://api.test` (API), `https://example.com/...` (external) |
| Date | `'2024-05-12 16:12:56'` |

Business values stay realistic (`'10 rue de Rivoli'`, a valid IBAN): they also document the format.
