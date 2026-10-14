# Tests — Essential rules

Read before writing or reviewing any test of the SDK. Each rule is detailed in the other files of this folder (see the table in `SKILL.md`).

## What we test, and how

- What we test: **repositories** (an HTTP call in, a DTO out), **DTOs** (reading and validating the API response), **REST clients** (headers, URL, errors) and the **traits or abstract classes** carrying a shared behavior.
- Only unit tests exist (`tests/Unit`, PHPUnit 12, PHP 8.4).
- Tests use **no network and no mocking library**: a real REST client of the SDK runs against a fake API (queued Guzzle responses), then we check the request sent and the DTO obtained.

## The rules

1. **One test = one `test_<subject>_case_<n>` method** (snake_case, `n` starting at 1 for each subject). The subject is the tested method (`test_get_legal_types_case_1`) or the cross-cutting behavior (`test_headers_case_2`).
2. **An English docblock above the test** describes the expected behavior with « should ». For an endpoint, it starts with `` `VERB path` ``. It is only left out when the name and the body already say everything (e.g. a `delete` without body).
3. **Arrange, act, assert**, separated by a blank line.
4. **Check the request first, then the response**: `assertLastApiRequestWas()`, then query, JSON or no body, then `assertInstanceOf()` of the DTO, then its values.
5. **`assertSame` by default.** `assertEquals` only to compare objects (`stdClass`).
6. **Exceptions always go through `assertThatExceptionWasThrown()`**, never `expectException()` nor a `try` / `fail` / `catch`.
7. **API data comes from the `Tests<Domain>Dtos` traits** (`mock…ApiData()`), with an override limited to the values the test checks. A full response is never copied into the test.
8. **Ids come from `TestCase::unique()`** (a counter reset before each test, hence fixed ids). An id checked by the test is hard-coded (`42`).
9. **No network nor mocking library**: a real REST client of the SDK runs against queued Guzzle responses (`TestsRestClient`).
10. **A shared behavior is tested through a fixture** (`tests/Fixtures/`), not through a concrete class picked at random.
11. **Every public method of a repository has at least one test**, plus one case per notable variation (dropped filter, empty response…). **Every method accepting includes also has an include test** (`include` query sent and include read, see `repositories.md`).
12. **Every DTO has an error case**: an invalid API response must make it not constructable, with its own exception. A new DTO is added to `notConstructableProvider()` in `ApiResponseDtoTest` (a guard test fails otherwise), and its specific error cases go in its own test file (see `dtos-and-shared-behavior.md`).
13. **To finish**: `vendor/bin/phpunit` must pass, and so must `vendor/bin/pint --test`.

## Writing readable test code

- If a helper is missing, add it to a trait of `tests/TestUtils/` rather than repeating the code in several tests (see `structure.md`).
- When a convention is not covered here, follow the closest pattern in the existing tests and tell the user the point is not documented. Do not invent a new convention.
