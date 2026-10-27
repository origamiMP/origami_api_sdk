# Code — Param bags

A param bag carries the parameters of a request to the API (query, JSON body, headers). An API repository method receives its param bag as an argument.

🚧 TODO — the rest of the param bag conventions (naming, splitting, value conversion, validation…) is not written yet (see the general rules in `SKILL.md`).

## Keys sent to the API

**A param bag declares the API key of every property it sends**, as `propertyName => api_key`: the reverse of a DTO (`getDefaultDataStructureToProperties()`), since the data goes out of the SDK instead of coming in. Nothing is deduced from the property names: a property left out, or not set, is not sent.

- A request param bag declares them in `getJsonRequestParamsList()`, `getFormRequestParamsList()` or `getQueryRequestParamsList()`, depending on where the API reads them: `'priceTaxExc' => 'price_tax_exc'`. The traits (`HasIncludes`, `HasFilters`, `HasPagination`, `HasSearch`) declare theirs the same way, merged by the param bag (`ListSellerRequestParamBag`).
- A param bag sent inside another one (a line, a translation, an address) declares them in `getPropertiesToApiKeys()` (`FormFieldOptionParamBag`).
- The key is the API's, whatever the property name: `'onCall247' => 'on_call_247'`, where a conversion of the name would give `on_call247`.

Guarded by `ParamBagTest::test_api_keys_case_1`: it fails on a public property without API key, or on a declared property that does not exist.

### Custom cast

A property value is converted by `ParamBag::castPropertyToEncodableType()` (nested param bag, array, backed enum, `Carbon` as `Y-m-d H:i:s`). A property needing another conversion declares a closure instead of its API key. The closure receives the property value and returns a single `api_key => value` pair, sent as is:

```php
'holidayDepartureDate' => fn (Carbon $date) => ['holiday_departure_date' => $date->format('Y-m-d')],
```

The closure is only called when the property is set. Returning anything else than a single pair with a string key throws a `LogicException`. Model: `CreateSellerRequestParamBag`.

## Includes

### The rule

**If an API request offers an include, whether by default or on demand, the param bag of the SDK method calling it must allow providing it.**

The SDK must not restrict what the API can do: an include it cannot request forces the application to make several calls, or to bypass the SDK. Default includes are concerned too: requesting them explicitly makes the call independent from a change of the API defaults, and allows requesting their nested includes (e.g. `pages.sections`).

The SDK is under development: the rule applies to the param bags and DTOs **that exist in the SDK**. Creating the missing DTOs of included resources is not mandatory, but the DTO still lists every API include, as a comment when its DTO does not exist (see `dtos.md`).

### What it implies

For each API repository method whose request offers at least one include:

1. The method receives a param bag, even if it has nothing else to send (optional or not: see `api-repositories.md`).
2. This param bag uses the `HasIncludes` trait, implements the matching contract, and sends its includes as query. Model: `GetCurrentUserRequestParamBag`.
3. Its `getRequestMainDto()` returns the DTO returned by the API repository method: for a list, the list DTO (`ListSellerRequestParamBag` → `SellerListDto`). This DTO decides which includes are accepted: `setIncludes()` silently drops the others, commented ones included.
4. This DTO declares the API includes (see `dtos.md`).

### Finding the includes of a request in the API

In `origami_api`:

- the transformer of the resource (`app/Api/Core/Transformers/...`): `$availableIncludes` and `$defaultIncludes`;
- `translations`, added as available and default by `BaseTranslatableTransformer` to every transformer extending it;
- the controller of the endpoint, which can force default includes (`setDefaultIncludes(['translations', 'pages'])` in `FormController`): they are still includes offered by the request.

How to test it: `references/tests/api-repositories.md`, « Include tests ».
