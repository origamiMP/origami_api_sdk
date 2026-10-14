# Code — Param bags

Un param bag porte les paramètres d'une requête vers l'API (query, body JSON, headers). Une méthode de repository qui appelle l'API reçoit son param bag en argument.

🚧 TODO — le reste des conventions des param bags (nommage, découpage, conversion des valeurs, validation…) n'est pas encore écrit. En attendant, suivez le pattern le plus proche dans `src/ParamBags/` et signalez à l'utilisateur que le point n'est pas documenté.

## Includes

### La règle

**Si une requête de l'API propose un include, que ce soit par défaut ou à la demande, le param bag de la méthode du SDK qui l'appelle doit permettre de le fournir.**

Le SDK ne doit pas restreindre ce que l'API sait faire. Un include que l'API propose mais que le SDK ne permet pas de demander oblige l'application qui utilise le SDK à faire plusieurs appels, ou à contourner le SDK.

Les includes par défaut sont concernés aussi : l'API les renvoie aujourd'hui sans qu'on les demande, mais les demander explicitement rend l'appel indépendant d'un changement de ces valeurs par défaut côté API, et permet de demander leurs includes imbriqués (ex : `pages.sections`).

### Portée : les fichiers qui existent dans le SDK

Le SDK est en cours de développement : il ne couvre pas encore toutes les ressources de l'API. La règle s'applique aux param bags et aux DTO **qui existent dans le SDK**. Créer les DTO des ressources incluses qui manquent n'est pas obligatoire.

En revanche, **chaque include proposé par l'API doit apparaître dans le `getAvailableIncludes()` du DTO**, que le SDK sache le lire ou non :
- un include dont le DTO existe dans le SDK est déclaré normalement (`'roles' => RoleDto::class`) ;
- un include dont le DTO n'existe pas encore est déclaré **en commentaire** (`// 'shipping_offers',`), comme dans `SellerDto` et `ModuleDto` ;
- un include **réservé** (renvoyé seulement à certains rôles, ou soumis à un feature flag) est déclaré de la même façon, selon que son DTO existe ou non.

Le lien vers l'API reste ainsi visible dans le code : ajouter un include revient à créer son DTO et à décommenter sa ligne. Le détail de cette déclaration est dans `dtos.md`.

### Ce que ça implique dans le SDK

Pour chaque méthode de repository dont la requête propose au moins un include :

1. **La méthode reçoit un param bag**, même si elle n'a pas d'autre paramètre à envoyer (ex : `get(int $id, ?GetFormRequestParamBag $paramBag = null)`, dont le param bag ne porte que des includes). Un param bag qui ne porte que des paramètres optionnels, comme des includes, peut être optionnel dans la signature (voir `repositories.md`).
2. **Ce param bag utilise le trait `HasIncludes`** (`OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes`), implémente le contrat correspondant, et envoie ses includes en query :

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

3. **`getRequestMainDto()` renvoie le DTO que renvoie la méthode de repository** qui utilise ce param bag. Pour une liste, c'est le DTO de liste (`ListSellerRequestParamBag` → `SellerListDto`, car `list()` renvoie un `SellerListDto`), pas le DTO d'un élément. C'est ce DTO qui décide des includes acceptés.
4. **Ce DTO déclare les includes de l'API** dans `getAvailableIncludes()` (voir la portée ci-dessus et `dtos.md`). Un DTO de liste reprend ceux de son élément. Les DTO inclus déclarent à leur tour leurs propres includes, pour que les includes imbriqués soient acceptés. `setIncludes()` retire silencieusement tout include que le DTO ne déclare pas, y compris ceux qui sont en commentaire.

### Trouver les includes d'une requête dans l'API

Dans le repo `origami_api` :

- le transformer de la ressource (`app/Api/Core/Transformers/...`) : ses propriétés `$availableIncludes` et `$defaultIncludes` ;
- `translations`, ajouté à la fois en disponible et par défaut par `BaseTranslatableTransformer`, dont héritent les transformers des ressources traduites ;
- le contrôleur de l'endpoint, qui peut imposer ses propres includes par défaut (ex : `setDefaultIncludes(['translations', 'pages'])` dans `FormController`).

Un include imposé par le contrôleur reste un include que la requête propose : la règle s'applique aussi.

### Tests

Chaque méthode concernée a un test d'include (voir `references/tests/repositories.md`, section « Les includes »).
