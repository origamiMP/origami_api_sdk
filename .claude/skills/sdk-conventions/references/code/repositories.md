# Code — Repositories

Un repository regroupe les appels à l'API d'une ressource. Chaque méthode envoie une requête (avec son param bag) et renvoie le DTO construit à partir de la réponse.

🚧 TODO — le reste des conventions des repositories (nommage des méthodes, décodage de la réponse, exceptions déclarées…) n'est pas encore écrit. En attendant, suivez le pattern le plus proche dans `src/Repositories/` et signalez à l'utilisateur que le point n'est pas documenté.

## Param bag optionnel

Un param bag peut être un **paramètre optionnel** de la méthode (`?GetFormRequestParamBag $paramBag = null`) **seulement s'il ne sert qu'à envoyer des paramètres optionnels** :

- des includes ;
- des filtres ;
- une recherche ;
- de la pagination ;
- des propriétés qui ne sont pas `required` dans ses règles de validation (`validationRulesForProperties()`).

Dès qu'un param bag porte au moins une valeur obligatoire pour l'API (une propriété `required` dans ses règles de validation, comme l'email de `GetUserGroupInvitationHistoryRequestParamBag`), il est obligatoire dans la signature.

Un param bag optionnel permet d'ajouter des includes à une méthode existante sans casser les applications qui l'appellent déjà sans argument (voir la règle des includes dans `param-bags.md`). Quand la méthode reçoit `null`, elle appelle l'API sans paramètre :

```php
public function get(int $id, ?GetFormRequestParamBag $paramBag = null): FormDto
{
    $response = $this->restClient->get("forms/{$id}", $paramBag);
    // ...
}
```
