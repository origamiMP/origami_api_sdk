# Tests — File layout

```
tests/
├── TestCase.php      # base class of every test, uses every trait of TestUtils
├── Fixtures/         # minimal classes to test a shared behavior
├── TestUtils/        # helper traits (TestsRestClient, TestsExceptions…) and mock data traits (Tests<Domain>Dtos)
└── Unit/             # the tests, laid out like src/
```

## Test files

- One test file per tested class, at the matching place: `src/Repositories/Api/Data/Form/OrigamiFormDataApiRepository.php` → `tests/Unit/Repositories/Api/Data/Form/OrigamiFormDataApiRepositoryTest.php`.
- Class `<TestedClass>Test`, extending `Tests\TestCase` (never `PHPUnit\Framework\TestCase` directly, to get every helper).
- A behavior shared by several classes is tested in the file of the class carrying it (`OrigamiRestClientTest` for every REST client, `RestApiRepositoryTest` for every repository, `Traits/…/<Trait>Test` for a trait).

## Fixtures (`tests/Fixtures/`)

Named `<Subject>Fixture`, with a docblock saying what it is for. When to use one is in `dtos-and-shared-behavior.md`.

To make a `protected` method testable, widen its visibility in the fixture. The technique depends on where the method is defined:

| The method is defined… | In the fixture | Example |
|---|---|---|
| in the parent class | redefine it as `public` and call `parent::method()` | `RestApiRepositoryFixture` |
| in a trait the fixture uses | `use TheTrait { method as public; }` | `FiltersParamBagFixture` |

Redefining does not work for a trait method: PHP copies it into the class, so the redefinition replaces it and `parent::` cannot find it. Do not use an alias (`as public exposedMethod`): the test would call a name that does not exist in the SDK.

## Helper traits (`tests/TestUtils/`)

- Any reusable helper goes in a `Tests<Subject>` trait; add it to an existing trait when the subject matches.
- `protected` methods, named `assert…` for assertions and `mock…` for mock data, with an English docblock when the name is not enough.
- A new trait is imported and used in `tests/TestCase.php`, in alphabetical order (both the `use` statements and the class `use` list).
