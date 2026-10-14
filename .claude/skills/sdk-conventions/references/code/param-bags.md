# Code — Param bags

A param bag carries the parameters of a request to the API (query, JSON body, headers). A repository method calling the API receives its param bag as an argument.

🚧 TODO — the rest of the param bag conventions (naming, splitting, value conversion, validation…) is not written yet. In the meantime, follow the closest pattern in `src/ParamBags/` and tell the user the point is not documented.

## Includes

### The rule

**If an API request offers an include, whether by default or on demand, the param bag of the SDK method calling it must allow providing it.**

The SDK must not restrict what the API can do. An include offered by the API but that the SDK cannot request forces the application using the SDK to make several calls, or to bypass the SDK.

Default includes are concerned too: the API returns them today without being asked, but requesting them explicitly makes the call independent from a change of these defaults on the API side, and allows requesting their nested includes (e.g. `pages.sections`).

### Scope: the files that exist in the SDK

The SDK is under development: it does not cover every API resource yet. The rule applies to the param bags and DTOs **that exist in the SDK**. Creating the missing DTOs of included resources is not mandatory.

However, **every include offered by the API must appear in the DTO's `getAvailableIncludes()`**, whether the SDK can read it or not:
- an include whose DTO exists in the SDK is declared normally (`'roles' => RoleDto::class`);
- an include whose DTO does not exist yet is declared **as a comment** (`// 'shipping_offers',`), as in `SellerDto` and `ModuleDto`;
- a **restricted** include (only returned to some roles, or behind a feature flag) is declared the same way, depending on whether its DTO exists.

The link to the API thus stays visible in the code: adding an include means creating its DTO and uncommenting its line. The details of this declaration are in `dtos.md`.

### What it implies in the SDK

For each repository method whose request offers at least one include:

1. **The method receives a param bag**, even if it has nothing else to send (e.g. `get(int $id, ?GetFormRequestParamBag $paramBag = null)`, whose param bag only carries includes). A param bag that only carries optional parameters, such as includes, can be optional in the signature (see `repositories.md`).
2. **This param bag uses the `HasIncludes` trait** (`OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes`), implements the matching contract, and sends its includes as query:

```php
class GetCurrentUserRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

    protected function validationRulesForProperties(): array
    {
        return $this->getIncludeValidationRules();
    }

    protected static function getRequestMainDto(): string
    {
        return UserDto::class;
    }
}
```

3. **`getRequestMainDto()` returns the DTO returned by the repository method** using this param bag. For a list, it is the list DTO (`ListSellerRequestParamBag` → `SellerListDto`, since `list()` returns a `SellerListDto`), not the DTO of an item. This DTO decides which includes are accepted.
4. **This DTO declares the API includes** in `getAvailableIncludes()` (see the scope above and `dtos.md`). A list DTO reuses those of its item. Included DTOs in turn declare their own includes, so that nested includes are accepted. `setIncludes()` silently drops any include the DTO does not declare, commented ones included.

### Finding the includes of a request in the API

In the `origami_api` repo:

- the transformer of the resource (`app/Api/Core/Transformers/...`): its `$availableIncludes` and `$defaultIncludes` properties;
- `translations`, added both as available and default by `BaseTranslatableTransformer`, which the transformers of translated resources extend;
- the controller of the endpoint, which can force its own default includes (e.g. `setDefaultIncludes(['translations', 'pages'])` in `FormController`).

An include forced by the controller is still an include offered by the request: the rule applies too.

### Tests

Each method concerned has an include test (see `references/tests/repositories.md`, section « Includes: check every method accepting some »).
