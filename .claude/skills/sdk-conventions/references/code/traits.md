# Code — Traits

A trait carries a behavior shared by several classes of the same kind: DTOs and param bags today, any other kind of class tomorrow.

## File organization

`src/Traits/<Kind>/`, `<Kind>` being the folder of the classes using it in `src/` (`Dtos`, `ParamBags`). A trait never goes in a domain subfolder: it is meant for any class of its kind.

## Names

`Has<Feature>` (`HasTimestamps`, `HasFilters`). The same name can exist in two kinds when the feature exists on both sides (`Dtos/HasIncludes` declares the includes a DTO accepts, `ParamBags/HasIncludes` sends the ones requested).

## Contract

A trait whose public methods are called by the users of the SDK, on any class using it, has a contract: an interface of the same name in `src/Contracts/Traits/<Kind>/`, declaring those public methods. A class using the trait implements the contract, imported as `Has<Feature>Contract` (see `contracts.md`):

```php
class ListSellerRequestParamBag extends RequestParamBag implements HasFiltersContract, HasIncludesContract, HasPaginationContract, HasSearchContract
{
    use HasFilters, HasIncludes, HasPagination, HasSearch;
```

Users of the SDK type-hint the contract, never the trait (`origami_admin_v2`: `Paginable::addPaginationToParamBag(HasPagination $paramBag)`, the contract). A trait only exposing properties (`HasTimestamps`) has no contract.

## Contents

In this order:

1. the constants (`HasFilters::BASE_FILTERS`);
2. the properties;
3. the public methods, then the protected and private ones. An abstract method stays with the methods of its visibility.

A `toArray()` overridden by a trait of DTOs comes last, below the protected methods, like in a DTO (see `dtos.md`): `Dtos/HasPagination`.

No rule sets the visibility of the properties, nor whether they come with getters and setters: it depends on their use. For instance, the traits of DTOs have public properties, like the DTOs; the traits of param bags have private ones, set and read through their public methods, since users of the SDK go through the contract.

## Public methods

A setter is named `set<Property>()` and takes the value without default; a getter `get<Property>()`, or `is<Property>()` for a boolean (`HasPagination`: `setPage()`, `getPage()`, `setWithoutPagination()`, `isWithoutPagination()`). A setter can adjust the value (`setPerPage()` caps it to 100) or filter it (`setFilters()` and `setIncludes()` drop what the request does not offer).

## What a trait gives to the class using it

A trait gives the class its part of the structure and of the rules, named after its feature, and the class merges them with its own:

| Kind | Methods | Example |
|---|---|---|
| DTO | `get<Feature>AsDataStructureToProperties()`, `get<Feature>ValidationRules()` | `HasTimestamps`, merged by `CustomFieldDto` |
| Param bag | `get<Feature>ParamsList()`, `get<Feature>ValidationRules()` | `HasIncludes`: `getIncludesParamsList()`, `getIncludesValidationRules()` |

What the class must provide is an abstract method of the trait: `getAvailableIncludes()`, `getRequestMainDto()`, `getAdditionalAvailableFilters()`, `initData()`.

## Tests

See `references/tests/dtos-and-shared-behavior.md`, « Traits and abstract classes: through a fixture ».
