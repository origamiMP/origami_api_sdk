# Code — DTO

Un DTO lit et valide une réponse de l'API, et expose ses valeurs en propriétés typées.

🚧 TODO — le reste des conventions des DTO (nommage, structure des données, validation, exceptions, `toArray()`…) n'est pas encore écrit. En attendant, suivez le pattern le plus proche dans `src/Dtos/` et signalez à l'utilisateur que le point n'est pas documenté.

## Includes

Un DTO dont la ressource a des includes côté API utilise le trait `OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes` et les déclare dans `getAvailableIncludes()`. La règle qui oblige à les déclarer, et à pouvoir les demander depuis un param bag, est dans `param-bags.md`.

### Déclarer les includes

`getAvailableIncludes()` liste **tous** les includes que l'API propose pour la ressource, y compris ceux par défaut et ceux qui sont réservés à certains rôles ou soumis à un feature flag :

- un include dont le DTO existe dans le SDK est associé à ce DTO ;
- un include dont le DTO n'existe pas encore est laissé **en commentaire**, pour garder le lien avec l'API.

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

Quand le DTO d'un include commenté est créé, on décommente la ligne en l'associant à ce DTO, et on lit l'include dans `getDefaultDataStructureToProperties()` (avec `collectIncluded()` ou `makeIncluded()`).

### DTO de liste : reprendre les includes de l'élément

**Est une liste tout DTO dont la réponse est une collection d'éléments**, quel que soit son nom et qu'elle soit paginée ou non : `SellerListDto` (paginée), `ModuleListDto` (non paginée), mais aussi `UserGroupInvitationHistoryDto`, dont la propriété `$data` contient des `UserGroupInvitationDto`. Pour le savoir, regardez la réponse de l'API (une collection Fractal) et la propriété qui porte les éléments dans le DTO, pas son nom.

Un DTO de liste propose les mêmes includes que son élément : l'API applique les includes demandés à chaque élément de la liste. Plutôt que de recopier la liste, le DTO de liste appelle celle de son élément :

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

Les deux listes restent ainsi identiques : un include ajouté (ou décommenté) dans l'élément est aussitôt disponible dans la liste. Une liste recopiée finit par diverger.
