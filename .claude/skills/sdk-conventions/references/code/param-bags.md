# Code — Param bags

A param bag carries the parameters of a request to the API (query, JSON body, headers). An API repository method receives its param bag as an argument.

There are two kinds:

- a **request param bag** extends `RequestParamBag`: the parameters of one call, one per API repository method (`ListSellerRequestParamBag`);
- a **nested param bag** extends `ParamBag`: an object sent inside a request (a translation, an address, a node of the form tree), as a property of a request param bag or of another nested param bag (`TaxTranslationParamBag`).

## File organization

`src/ParamBags/<Family>/<Domain>/`, the same family and domain as the API repository calling the request (`Data/Seller/`, `Guest/Tax/`, `Oauth/`).

A nested param bag goes:

- in a subfolder named after the request param bag, without `RequestParamBag`, when only this request uses it (`Data/Seller/CreateSeller/CreateSellerAddressParamBag`);
- in the domain folder when several requests of the domain use it (`Data/Tax/TaxTranslationParamBag`, used by `CreateTaxRequestParamBag` and `UpdateTaxRequestParamBag`);
- at the root of the family when several domains use it (`Data/AddressParamBag`).

## Class names

- Request param bag: `<Verb><Resource>RequestParamBag`, the verb being the method of the API repository and the resource in the singular: `ListCountryRequestParamBag` for `OrigamiCountryGuestApiRepository::list()`, `CreateSellerBankAccountRequestParamBag` for `OrigamiSellerBankAccountDataApiRepository::create()`.
- Nested param bag: `<Resource><Part>ParamBag` (`TaxTranslationParamBag`, `FormFieldOptionParamBag`), prefixed by the request when it lives in its subfolder (`CreateSellerLegalInformationParamBag`).

## Class contents

In this order:

1. the traits;
2. the properties;
3. the constructor, when the param bag sets a fixed value (`GetOauthTokenWithPasswordRequestParamBag`);
4. `getAdditionalAvailableFilters()`, when the param bag uses `HasFilters`;
5. the API keys (see « Keys sent to the API »): `getPropertiesToApiKeys()` for a nested param bag, otherwise `getJsonRequestParamsList()`, `getFormRequestParamsList()`, then `getQueryRequestParamsList()`;
6. `getRequestMainDto()`, when the param bag uses `HasIncludes`.

A param bag calls the parent method of a list or of the rules (`...parent::getJsonRequestParamsList()`) only when its parent declares some itself (`GetOauthTokenRequestParamBag` for its two subclasses), never when the parent is `RequestParamBag` or `ParamBag`.

## Properties

- `public` and typed, named in camelCase. A property left unset is not sent.
- A backed enum, a `Carbon`, a nested param bag or an array of them is converted automatically (see « Custom cast »).
- An array of nested param bags is documented by `/** @var array<int, TaxTranslationParamBag> */`.

## Cross-cutting traits

A request param bag offering a common feature of the API uses its trait from `Traits\ParamBags`, and implements its contract from `Contracts\Traits\ParamBags`, imported as `<Trait>Contract`:

| Trait | Sends (query) | The param bag adds |
|---|---|---|
| `HasIncludes` | `include` | `getRequestMainDto()` (see « Includes ») |
| `HasFilters` | `filter` | `getAdditionalAvailableFilters()`: the filters of the endpoint, on top of the dates of `BASE_FILTERS` |
| `HasPagination` | `page`, `item_per_page`, `without_pagination` | – |
| `HasSearch` | `search` | – |

The param bag merges the API keys of each trait with its own: `...$this->getIncludesParamsList()` (`ListSellerRequestParamBag`).

## No validation rules

A param bag declares no validation rules: the API validates what it receives, and answers an invalid request with a 422 that the SDK throws as `OrigamiApiUnprocessableEntityException` (see `exceptions.md`).

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

## Tests

What a request param bag sends is tested through its API repository (`references/tests/api-repositories.md`). The behavior shared by every param bag is tested in `ParamBagTest`, and each trait in `Traits/ParamBags/<Trait>Test`, through a fixture (`references/tests/dtos-and-shared-behavior.md`).
