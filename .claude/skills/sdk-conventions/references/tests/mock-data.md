# Tests — Mock data

The data imitating API responses is centralized in the `tests/TestUtils/Tests<Domain>Dtos.php` traits. A test only holds the values it checks, which keeps it short and shows what matters. When the API format changes, only one place needs fixing.

## One trait per domain

- Name: `Tests<Domain>Dtos` (`TestsSellerDtos`, `TestsFormDtos`, `TestsUserDtos`…), with a domain following the folders of `src/Dtos/`.
- A new domain → a new trait, registered in `TestCase` (see `structure.md`). A new DTO in an existing domain → one more method in the trait of this domain.

## `mock<Dto>ApiData()`: the raw response

One method per DTO, returning an array in the API response format (snake_case keys). The test overrides only the values it cares about:

```php
protected function mockTaxApiData(array $override = []): array
{
    $baseData = [
        'id'           => TestCase::unique(),
        'value'        => 20.0,
        'type'         => TaxDtoTypeEnum::PERCENTAGE->value,
        'id_scs_tax'   => null,
        'is_default'   => false,
        'module_id'    => null,
        'translations' => ['data' => [
            ['locale' => 'en', 'default' => true, 'language_id' => TestCase::unique(), 'name' => 'VAT'],
        ]],
    ];

    return array_merge($baseData, $override);
}
```

- Signature: `protected function mock<Dto>ApiData(array $override = []): array`, with the DTO name without the `Dto` suffix (`mockSellerBankAccountApiData`, `mockFormFieldOptionApiData`).
- Body: `$baseData = [...]`, then `return array_merge($baseData, $override);`.
- **Every key the DTO validates** (`required` or `present`) is there, so that the default data builds a valid DTO. Optional keys (includes, `fields_count`…) are missing by default, and the test adds them by override.
- **Ids**: `TestCase::unique()`, foreign keys included (`user_group_id`, `language_id`).
- **Enums**: `XxxEnum::CASE->value`, so that the data follows if a value changes.
- **Dates**: `'2024-05-12 16:12:56'`.
- **Includes**: wrapped as Fractal does, `'translations' => ['data' => [...]]`, and `['data' => []]` for an empty list.
- A child node can have its own method (`mockFormPageApiData()`, `mockFormSectionApiData()`…), which the test assembles by override to build a tree.
- A **nested object that is not a DTO** can also have its method, when tests need to change one of its fields. `array_merge` only merges the first level: without a dedicated method, the whole object would have to be rewritten, or changed afterwards (`$data['config']['id'] = 42`). Name it after the DTO and the key (`mockCustomFieldModelValueConfigApiData()` for the `config` key of `CustomFieldModelValueDto`):

```php
$this->mockCustomFieldModelValueApiData([
    'config' => $this->mockCustomFieldModelValueConfigApiData(['type' => 'unknown']),
]);
```
- A response whose shape has no dedicated DTO (stats, `check-pending`) can be written directly in the test.

Ids come from `TestCase::unique()`, a counter reset before each test: the ids of a test are thus always the same, whatever the execution order. An id checked by the test is still passed by override (`['id' => 42]`), so that the test reads without knowing the order of the calls to `unique()`.

### Matching the actual API format

Mock data must look like what the API actually returns, otherwise the test passes while the SDK would break in production. When in doubt, read the matching transformer in the `origami_api` repo (`app/Api/Core/Transformers/...`) before writing the data. Known points:

- **The API is in PHP: an empty map is encoded as `[]`**, not `{}` (e.g. no feature flag). A test covering this case uses `jsonResponse([])`.
- Conversely, to get `{}` in the response, pass `(object) []`: `'params' => (object) []`.
- An associative array is encoded as a JSON object and becomes a `stdClass` again on the SDK side. If a value must arrive as an object, use an associative array, and add a comment when it is not obvious:

```php
// Associative (not a list) : encoded as a JSON object, so it decodes back into a stdClass
// to match ModuleDto's typed `object` properties.
'configurations' => ['is_enabled' => true],
```

## `mock<Dto>Dto()`: the built DTO

When a test needs the DTO itself (DTO tests, authenticated client…), add next to it a method building it from the raw data:

```php
protected function mockSellerDto(array $override = []): SellerDto
{
    return new SellerDto(Obj::fromArray($this->mockSellerApiData($override)));
}
```

`Obj::fromArray()` turns the array into objects, as decoding the JSON response would.

### Testing a missing key

An override cannot remove a key (`array_merge` adds or replaces, it does not delete). To test a response missing a mandatory key, start from the raw data, remove the key with `unset()`, then build the DTO directly:

```php
public function test_value_case_3(): void
{
    $apiData = $this->mockCustomFieldModelValueApiData();
    unset($apiData['value']);

    $this->assertThatExceptionWasThrown(
        fn () => new CustomFieldModelValueDto(Obj::fromArray($apiData)),
        CustomFieldModelValueDtoNotConstructableException::class,
    );
}
```

This is the only case where a test builds a DTO itself instead of going through `mock<Dto>Dto()`.

## Generic responses

In `TestCase` and `TestsErrorDtos`:

| Helper | For |
|---|---|
| `mockApiPaginatedListData(array $items)` | Single-page paginated list (`data` + `meta.pagination`) |
| `mockApiPagination(int $count)` | The `pagination` part alone |
| `mockApiErrorData($status, $detail, $code, ?$field)` | One error of the `errors` list |
| `mockApiErrorsResponse($status, ...$errors)` | A full error response |
