# Tests — Mock data

API response data lives in the `tests/TestUtils/Tests<Domain>Dtos` traits (one per folder of `src/Dtos/`, registered in `TestCase`). A test only holds the values it checks; when the API format changes, one place is fixed.

## `mock<Dto>ApiData(array $override = []): array`

One method per DTO (name without the `Dto` suffix), returning `[...$baseData, ...$override]` in the API format (snake_case keys). Model: `TestsTaxDtos::mockTaxApiData()`.

- Every key the DTO requires is there, so that the default data builds a valid DTO (`additional_information` included, as `[]`). Optional keys (includes, keys of `getOptionalKeys()` such as `fields_count`) are left out, and added by override.
- Ids: `TestCase::unique()`, foreign keys included. It is a counter reset before each test, so ids are always the same; an id checked by the test is still passed by override (`['id' => 42]`).
- Enums: `XxxEnum::CASE->value`. Dates: `'2024-05-12 16:12:56'`. Includes: wrapped as Fractal does, `['data' => [...]]`.
- A child node can have its own method, assembled by override to build a tree (`mockFormPageApiData()`…). So can a nested object that is not a DTO, when tests must change one of its fields, since the spread only merges the first level (`mockCustomFieldModelValueConfigApiData()` for the `config` key of `CustomFieldModelValueDto`).
- A response without dedicated DTO (stats, `check-pending`) can be written in the test.

## `mock<Dto>Dto(array $override = []): <Dto>`

Builds the DTO from the raw data, for DTO tests: `new SellerDto(Obj::fromArray($this->mockSellerApiData($override)))`.

An override cannot remove a key. To test a missing key, take the raw data, `unset()` the key and build the DTO directly (`CustomFieldModelValueDtoTest::test_value_case_3`), the only case where a test builds a DTO itself.

## Matching the actual API format

Mock data must look like what the API returns, otherwise the test passes while the SDK would break. When in doubt, read the transformer in `origami_api` (`app/Api/Core/Transformers/...`). Known points:

- the API is in PHP: an empty map is encoded as `[]`, not `{}` (test it with `jsonResponse([])`); to get `{}`, pass `(object) []`;
- an associative array becomes a JSON object, then a `stdClass` in the SDK: say so in a comment when it is not obvious (`TestsModuleDtos`).

## Generic responses

| Helper | For |
|---|---|
| `mockApiPaginatedListData(array $items)` | Single-page paginated list (`TestCase`) |
| `mockApiErrorsResponse($status, ...$errors)`, `mockApiErrorData(...)` | API error response (`TestsErrorDtos`) |
