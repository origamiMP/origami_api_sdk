# Tests — File layout and naming

## Layout

```
tests/
├── TestCase.php          # base class of every test, uses every trait of TestUtils
├── Fixtures/             # minimal classes to test a shared behavior
│   ├── RestApiRepositoryFixture.php
│   ├── IncludesDtoFixture.php
│   └── IncludesParamBagFixture.php   # …
├── TestUtils/            # helper and mock data traits
│   ├── TestsRestClient.php        # fake HTTP client + assertions on the request
│   ├── TestsExceptions.php        # assertThatExceptionWasThrown()
│   ├── TestsErrorDtos.php         # API error responses
│   ├── TestsArrayableDtos.php     # assertions on toArray()
│   └── Tests<Domain>Dtos.php      # mock data of a domain (Seller, Form, User…)
└── Unit/                 # the tests, laid out like src/
    ├── Dtos/User/UserDtoTest.php
    ├── Repositories/Api/Data/Seller/OrigamiSellerDataApiRepositoryTest.php
    ├── Repositories/Client/OrigamiRestClientTest.php
    ├── Traits/Dtos/HasIncludesTest.php
    └── Traits/ParamBags/HasIncludesTest.php
```

## Test files

- **One test file per tested class**, at the place matching the class in `src/`: `src/Repositories/Api/Data/Form/OrigamiFormDataApiRepository.php` is tested by `tests/Unit/Repositories/Api/Data/Form/OrigamiFormDataApiRepositoryTest.php`.
- The class is named `<TestedClass>Test`, in the matching `Tests\Unit\...` namespace, and extends `Tests\TestCase` (never `PHPUnit\Framework\TestCase` directly, to get every helper).
- A behavior shared by several classes is tested in the file of the class carrying it: the behavior of every REST client is in `OrigamiRestClientTest`, the one of every repository in `RestApiRepositoryTest`, the one of the `HasIncludes` trait in `Traits/Dtos/HasIncludesTest`.

## Fixtures (`tests/Fixtures/`)

A fixture is a minimal class, written only for the tests, extending an abstract class or using a trait. It allows testing a general behavior without going through a concrete class of the SDK, whose own behavior would blur the test.

- Name: `<Subject>Fixture` (e.g. `RestApiRepositoryFixture`, `IncludesDtoFixture`), namespace `Tests\Fixtures`.
- A docblock says what it is for and what it exposes.
- It only contains what the test needs.

### Making a `protected` method testable

The technique depends on where the method is defined:

| The `protected` method is defined… | In the fixture |
|---|---|
| in the parent class (or in a trait of the parent class) | Redefine it as `public` and call `parent::method()` |
| in a trait the fixture uses itself | `use TheTrait { method as public; }` |

**Method of the parent class**: redefine it as `public`.

```php
/**
 * Minimal RestApiRepository, exposing its protected helpers to test the behavior shared by every repository.
 */
class RestApiRepositoryFixture extends RestApiRepository
{
    public function decodeResponse(ResponseInterface $response): object
    {
        return parent::decodeResponse($response);
    }
}
```

**Method of a trait used by the fixture**: change its visibility in the `use` statement.

```php
class FiltersParamBagFixture extends RequestParamBag
{
    use HasFilters {
        getFiltersValidationRules as public;
    }

    // ...
}
```

Redefining does not work in this second case. PHP copies the methods of a trait into the class using it: the redefined method replaces the trait's one, and `parent::` looks for it in the parent class, where it does not exist (`Call to undefined method`).

Do not use an alias (`method as public exposedMethod;`): the test would call a name that does not exist in the SDK.

The details (DTO fixture, « NotConstructable » exception) are in `dtos-and-shared-behavior.md`.

## Helper traits (`tests/TestUtils/`)

- Any reusable helper goes in a trait of `tests/TestUtils/`, namespace `Tests\TestUtils`, named `Tests<Subject>`: `TestsExceptions`, `TestsRestClient`, `Tests<Domain>Dtos`.
- Add a helper to an existing trait if the subject matches, otherwise create a trait.
- Methods are `protected`. Assertions are named `assert…` (`assertLastApiRequestWas`, `assertThatExceptionWasThrown`, `assertArrayKeysAreCamelCase`), mock data `mock…`.
- Each helper whose name is not enough has an English docblock (what it does, its quirks, e.g. « Query string values are always strings once sent »).

### Registering a new trait

A new trait must be imported and used in `tests/TestCase.php`, **in alphabetical order**, both in the `use` statements at the top of the file and in the class `use` list:

```php
use Tests\TestUtils\TestsErrorDtos;
use Tests\TestUtils\TestsExceptions;
use Tests\TestUtils\TestsFormDtos;
// ...

abstract class TestCase extends BaseTestCase
{
    use TestsArrayableDtos,
        TestsCountryDtos,
        // ...
        TestsFormDtos,
        // ...
```

`TestCase` itself only holds the truly cross-cutting helpers (`unique()`, `mockApiPagination()`, `mockApiPaginatedListData()`).
