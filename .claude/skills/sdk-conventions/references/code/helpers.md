# Code — Helpers

A helper is generic code, without any business logic of the API: it could be reused as is in another project. The business logic belongs to the DTOs, param bags and repositories.

## File organization

`src/Helpers/<Subject>/`, one subfolder per subject:

| Subfolder | Subject |
|---|---|
| `Support/` | Primitive types (array, object…): `Obj` |
| `Validation/` | Running the Laravel validation outside of Laravel: `Validator`, `DummyTranslator` |

A helper about the SDK itself stays at the root of `src/Helpers/` (`OrigamiApiSdk::getVersion()`). A new subject gets its subfolder and its line in the table above.

## Names

A class in the singular, named after the concept it handles (`Obj` for an object).

## Contents

- `static` methods, without state (`Obj::fromArray()`).
- Parameters and return value typed, `mixed` when any value is accepted (`Obj::get(mixed $target, …)`). A method implementing an interface of a library keeps the signature of the interface (`DummyTranslator`).
- 📢 Documented exception: a class adapting a component of Laravel is instantiated, not static (`Validator` extends the Laravel validator with `DummyTranslator`, which returns the keys of the messages until a real translation exists).

## Documentation

Every helper has a PHPDoc with a short explanation of what it does (`Obj::toArray()`: « Converts an object to an array, nested objects included. »). A helper replacing a function of PHP or Laravel also says what it changes compared to it (`Obj::get()`: `data_get()` returns the default value instead of `null` for a property of an object set to `null`).

## Tests

A helper with logic of its own has its test file, `tests/Unit/Helpers/<Subject>/<Helper>Test.php`, like any class (see `references/tests/structure.md`).
