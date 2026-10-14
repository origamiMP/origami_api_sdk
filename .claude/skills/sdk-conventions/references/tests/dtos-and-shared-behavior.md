# Tests — DTOs, traits and shared behaviors

## Testing a DTO

The normal path of a DTO (reading the response of an endpoint) is already covered by the tests of the repository returning it. A dedicated DTO test (`tests/Unit/Dtos/<Domain>/<Dto>Test.php`) is written for what repository tests do not show:

- **includes** (present, missing, malformed);
- the **accepted values** of a validated field (each enum case the API can send);
- **error cases**: an invalid value must make the DTO not constructable.

The DTO is built with `mock<Dto>Dto()` (see `mock-data.md`), overriding what the case involves:

```php
/**
 * The `roles` include should be read as a collection of roles.
 */
public function test_roles_case_1(): void
{
    $userDto = $this->mockUserDto(['roles' => ['data' => [
        $this->mockRoleApiData(['id' => 1]),
        $this->mockRoleApiData(['id' => 2]),
    ]]]);

    $this->assertContainsOnlyInstancesOf(RoleDto::class, $userDto->roles);
    $this->assertSame([1, 2], $userDto->roles->pluck('id')->all());
}

/**
 * An include not wrapped in a `data` key should make the user not constructable.
 */
public function test_roles_case_3(): void
{
    $this->assertThatExceptionWasThrown(
        fn () => $this->mockUserDto(['roles' => [$this->mockRoleApiData()]]),
        UserDtoNotConstructableException::class,
    );
}
```

Here, the test only has two blocks (building, assertions), since building is the tested action.

## Error cases of every DTO

Every DTO must reject an invalid API response with **its own** « NotConstructable » exception (`SellerDtoNotConstructableException` for `SellerDto`…), so that the caller knows which part of the response is wrong. This is checked for every DTO at once by a guard test in `tests/Unit/Dtos/ApiResponseDtoTest.php`:

- `test_not_constructable_case_1` builds each DTO listed in `notConstructableProvider()` from an invalid response, and checks the exception class and that its message starts with `[<DtoName>]`;
- `test_not_constructable_case_2` checks that every DTO of `src/Dtos/` is listed in the provider: it fails as soon as a new DTO is forgotten.

**When you create a DTO**, add its line to the provider: the DTO, its exception, and an invalid response. An empty response (`(object) []`) is enough for any DTO with a required field; for a DTO without any required field, give a value of the wrong type:

```php
public static function notConstructableProvider(): array
{
    return [
        'CountryDto'         => [CountryDto::class, CountryDtoNotConstructableException::class, (object) []],
        'FeatureFlagListDto' => [FeatureFlagListDto::class, FeatureFlagListDtoNotConstructableException::class, (object) ['some_flag' => 'yes']],
        // ...
    ];
}
```

The provider only checks that the DTO rejects an invalid response with the right exception. **The error cases specific to a DTO** (a malformed include, an unexpected enum value, a missing key that matters…) go in its own test file, as in the `test_roles_case_3` example above.

## Guard tests of a convention

When a test guards a convention rather than a precise behavior, the class has a docblock explaining the convention and what breaks if it is not followed:

```php
/**
 * Guards that `SellerDto` accepts every legacy seller state the central API can send in a seller's `state` field
 * (`seller_information.state`). `SellerDto` validates it against `SellerDtoStateEnum`: a missing case makes the
 * Dto — and the whole `SellerListDto` holding it — not constructable.
 */
class SellerDtoTest extends TestCase
```

This is the case of `ApiResponseDtoTest`: the keys of `toArray()` stay camelCase (checked with `assertArrayKeysAreCamelCase()`), and every DTO has its error case (see above).

## Shared behavior: going through a fixture

A trait or an abstract class is tested **through a fixture** (`tests/Fixtures/`): a minimal class adding nothing to the tested behavior. Testing through a concrete DTO or repository would mix their own behavior into the test, and the test would break if this class changed.

The test class says so in its docblock:

```php
/**
 * Behavior shared by every Dto with includes, tested through a fixture (which adds nothing to it).
 */
class HasIncludesTest extends TestCase
```

### Fixture of a class with protected methods

Widen the visibility of the method: by redefining it if it comes from the parent class, with `use TheTrait { method as public; }` if it comes from a trait used by the fixture (both cases are detailed in `structure.md`, section « Making a `protected` method testable »). Then instantiate the fixture in `setUp()` if every test uses it:

```php
private RestApiRepositoryFixture $repository;

protected function setUp(): void
{
    parent::setUp();

    $this->repository = new RestApiRepositoryFixture($this->mockGuestRestClientReturning());
}
```

`parent::setUp()` must be called: it resets the counter of `TestCase::unique()`.

### DTO fixture

A DTO fixture extends `ApiResponseDto`, uses the tested trait, only declares the properties the test needs, and returns an anonymous exception as « NotConstructable » exception:

```php
class IncludesDtoFixture extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    /** @var Collection<int, IncludesDtoFixture> */
    public Collection $children;

    public static function getAvailableIncludes(): array
    {
        return [
            'children' => self::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'       => 'id',
            'children' => fn (object $children) => $this->children = $this->collectIncluded($children, self::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id' => ['required', 'integer'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new class($msg, previous: $previous) extends ApiResponseDtoNotConstructableException {};
    }
}
```

A fixture can include itself (`self::class`) to test nested includes without depending on another DTO. Tests build it with a small private helper:

```php
private function makeFixture(array $apiData): IncludesDtoFixture
{
    return new IncludesDtoFixture(Obj::fromArray($apiData));
}
```

A private helper specific to one test class is placed at the end of this class.

### Linked fixtures

A fixture can rely on another fixture when the tested behavior links two classes. For instance, the `HasIncludes` trait of param bags only keeps the includes that the DTO returned by the request declares available. `IncludesParamBagFixture` therefore returns `IncludesDtoFixture`, already written to test the `HasIncludes` trait of DTOs:

```php
class IncludesParamBagFixture extends RequestParamBag
{
    use HasIncludes;

    // ...

    protected static function getRequestMainDto(): string
    {
        return IncludesDtoFixture::class;
    }
}
```

Reuse an existing fixture rather than writing a second one doing the same thing.

### Varying a fixture for a single test

When a single test needs a variant of the fixture, create in this test an **anonymous class** extending it and redefining the bare minimum, rather than a new fixture:

```php
/**
 * When the Dto returned by the request declares no includes, every include should be ignored.
 */
public function test_set_includes_case_4(): void
{
    $paramBag = new class extends IncludesParamBagFixture
    {
        protected static function getRequestMainDto(): string
        {
            return FormTranslationDto::class;
        }
    };

    $paramBag->setIncludes(['children']);

    $this->assertSame([], $paramBag->getIncludes());
}
```

If several tests need the same variant, write a real fixture in `tests/Fixtures/`.
