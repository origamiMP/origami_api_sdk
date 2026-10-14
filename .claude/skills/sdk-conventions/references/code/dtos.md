# Code — DTOs

A DTO reads and validates an API response, and exposes its values as typed properties.

🚧 TODO — the rest of the DTO conventions (naming, data structure, validation, exceptions, `toArray()`…) is not written yet. In the meantime, follow the closest pattern in `src/Dtos/` and tell the user the point is not documented.

## Includes

A DTO whose resource has includes on the API side uses the `OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes` trait and declares them in `getAvailableIncludes()`. The rule requiring to declare them, and to be able to request them from a param bag, is in `param-bags.md`.

### Declaring includes

`getAvailableIncludes()` lists **all** the includes the API offers for the resource, including default ones and those restricted to some roles or behind a feature flag:

- an include whose DTO exists in the SDK is mapped to this DTO;
- an include whose DTO does not exist yet is left **as a comment**, to keep the link with the API.

```php
public static function getAvailableIncludes(): array
{
    return [
        'user_groups' => UserGroupDto::class,
        'roles'       => RoleDto::class,
        // 'user_group_users',
        // 'module',
    ];
}
```

When the DTO of a commented include is created, uncomment the line mapping it to this DTO, and read the include in `getDefaultDataStructureToProperties()` (with `collectIncluded()` or `makeIncluded()`).

### List DTOs: reuse the includes of the item

**Any DTO whose response is a collection of items is a list**, whatever its name and whether it is paginated or not: `SellerListDto` (paginated), `ModuleListDto` (not paginated), but also `UserGroupInvitationHistoryDto`, whose `$data` property holds `UserGroupInvitationDto`s. To tell, look at the API response (a Fractal collection) and at the DTO property holding the items, not at its name.

A list DTO offers the same includes as its item: the API applies the requested includes to each item of the list. Rather than copying the list, the list DTO calls the one of its item:

```php
class SellerListDto extends ApiResponseDto
{
    use HasIncludes, HasPagination;

    public static function getAvailableIncludes(): array
    {
        return SellerDto::getAvailableIncludes();
    }

    // ...
}
```

Both lists thus stay identical: an include added (or uncommented) in the item is immediately available in the list. A copied list ends up diverging.
