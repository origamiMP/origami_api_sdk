# Tests — DTOs, traits and shared behaviors

## DTO tests

Reading a valid response is covered by the tests of the API repository returning the DTO. A dedicated test file (`tests/Unit/Dtos/<Domain>/<Dto>Test.php`, built with `mock<Dto>Dto()`) covers what API repository tests do not show:

- includes: present, missing, malformed (`UserDtoTest::test_roles_case_1` to `_3`), especially those no API repository reads;
- the accepted values of an enum field (`SellerDtoTest`: each state the API can send);
- the error cases specific to the DTO (a missing key that matters, an unexpected value).

## Error case of every DTO

Every DTO must reject an invalid response with **its own** « NotConstructable » exception, so that the caller knows which part of the response is wrong. `ApiResponseDtoTest` checks it for all of them:

- `test_not_constructable_case_1` builds each DTO of `notConstructableProvider()` from an invalid response, and checks the exception and its `[<DtoName>]` message prefix;
- `test_not_constructable_case_2` fails if a DTO of `src/Dtos/` is missing from the provider.

**When you create a DTO**, add its line to the provider: the DTO, its exception, and an invalid response — `(object) []` when the DTO has a required field, otherwise a value of the wrong type.

## Guard tests

A test guarding a convention rather than a precise behavior has a class docblock explaining the convention and what breaks without it (`SellerDtoTest`, `ApiResponseDtoTest`, `ParamBagTest`, `OrigamiApiSdkExceptionTest`).

## Traits and abstract classes: through a fixture

A trait or an abstract class is tested **through a fixture** (`tests/Fixtures/`), a minimal class adding nothing to the tested behavior, never through a concrete class whose own behavior would blur the test. The test class says so: « Behavior shared by every … , tested through a fixture (which adds nothing to it). »

A trait with logic of its own (a computed value, a filter, an exception thrown, its own `toArray()`) has its test file, `tests/Unit/Traits/<Kind>/<Trait>Test.php`. A trait only declaring properties with their structure and rules (`HasTimestamps`, `HasSearch`) is covered by the tests of the classes using it.

- **Protected methods**: see `structure.md` for widening their visibility. If every test uses the fixture, build it in `setUp()`, after `parent::setUp()` (which resets `TestCase::unique()`) (`RestApiRepositoryTest`).
- **DTO fixture**: extends `ApiResponseDto`, uses the tested trait, only declares the properties the test needs, and returns an anonymous exception as « NotConstructable » exception. It can include itself (`self::class`) to test nested includes (`IncludesDtoFixture`). Tests build it with a private `makeFixture()` helper, at the end of the class.
- **Linked fixtures**: reuse an existing fixture when the behavior links two classes (`IncludesParamBagFixture` returns `IncludesDtoFixture`).
- **Variant for a single test**: an anonymous class extending the fixture, redefining the bare minimum (`Traits/ParamBags/HasIncludesTest::test_set_includes_case_4`). If several tests need it, write a real fixture.
