# Code — DTOs

A DTO reads and validates an API response, and exposes its values as typed properties.

🚧 TODO — the rest of the DTO conventions (naming, data structure, validation, exceptions, `toArray()`…) is not written yet (see the general rules in `SKILL.md`).

## Declaring includes

A DTO whose resource has includes on the API side uses the `HasIncludes` trait (`Traits\Dtos`). Why every include must be declared is in `param-bags.md`.

`getAvailableIncludes()` lists **all** the includes the API offers for the resource, default ones and those restricted to some roles or behind a feature flag included:

- an include whose DTO exists in the SDK is mapped to it, and read in `getDefaultDataStructureToProperties()` with `collectIncluded()` or `makeIncluded()`;
- an include whose DTO does not exist yet stays **as a comment**, to keep the link with the API.

```php
'user_groups' => UserGroupDto::class,
// 'user_group_users',
```

Adding an include later means creating its DTO, uncommenting the line and reading it. Full example: `SellerDto`.

## List DTOs

**Any DTO whose response is a collection of items is a list**, whatever its name and whether it is paginated or not: `SellerListDto`, `ModuleListDto`, but also `UserGroupInvitationHistoryDto`. Look at the API response (a Fractal collection) and at the DTO property holding the items, not at the name.

A list DTO offers the same includes as its item, since the API applies them to each item. It does not copy them, it returns those of its item, so that both never diverge:

```php
public static function getAvailableIncludes(): array
{
    return SellerDto::getAvailableIncludes();
}
```
