# Tests — Données mockées

Les données qui imitent les réponses de l'API sont centralisées dans les traits `tests/TestUtils/Tests<Domaine>Dtos.php`. Un test ne contient que les valeurs qu'il vérifie, ce qui le rend court et montre ce qui compte. Quand le format de l'API change, on ne corrige qu'un endroit.

## Un trait par domaine

- Nom : `Tests<Domaine>Dtos` (`TestsSellerDtos`, `TestsFormDtos`, `TestsUserDtos`…), avec un domaine qui suit les dossiers de `src/Dtos/`.
- Un domaine nouveau → un nouveau trait, enregistré dans `TestCase` (voir `structure.md`). Un DTO nouveau dans un domaine existant → une méthode de plus dans le trait de ce domaine.

## `mock<Dto>ApiData()` : la réponse brute

Une méthode par DTO, qui renvoie un tableau au format de la réponse de l'API (clés snake_case). Le test passe en override les seules valeurs qui l'intéressent :

```php
protected function mockTaxApiData(array $override = []): array
{
    $baseData = [
        'id'           => TestCase::unique(),
        'value'        => 20.0,
        'type'         => TaxDtoTypeEnum::PERCENTAGE->value,
        'id_scs_tax'   => null,
        'is_default'   => false,
        'module_id'    => null,
        'translations' => ['data' => [
            ['locale' => 'en', 'default' => true, 'language_id' => TestCase::unique(), 'name' => 'VAT'],
        ]],
    ];

    return array_merge($baseData, $override);
}
```

- Signature : `protected function mock<Dto>ApiData(array $override = []): array`, avec le nom du DTO sans le suffixe `Dto` (`mockSellerBankAccountApiData`, `mockFormFieldOptionApiData`).
- Corps : `$baseData = [...]`, puis `return array_merge($baseData, $override);`.
- **Toutes les clés que le DTO valide** (`required` ou `present`) sont présentes, pour que les données par défaut construisent un DTO valide. Les clés optionnelles (includes, `fields_count`…) sont absentes par défaut, et le test les ajoute par override.
- **Ids** : `TestCase::unique()`, y compris pour les clés étrangères (`user_group_id`, `language_id`).
- **Enums** : `XxxEnum::CASE->value`, pour que les données suivent si une valeur change.
- **Dates** : `'2024-05-12 16:12:56'`.
- **Includes** : enveloppés comme le fait Fractal, `'translations' => ['data' => [...]]`, et `['data' => []]` pour une liste vide.
- Un nœud enfant peut avoir sa propre méthode (`mockFormPageApiData()`, `mockFormSectionApiData()`…), que le test assemble par override pour construire un arbre.
- Un **objet imbriqué qui n'est pas un DTO** peut aussi avoir sa méthode, quand les tests doivent en changer un champ. `array_merge` ne fusionne que le premier niveau : sans méthode dédiée, il faudrait réécrire tout l'objet, ou le modifier après coup (`$data['config']['id'] = 42`). On la nomme d'après le DTO et la clé (`mockCustomFieldModelValueConfigApiData()` pour la clé `config` de `CustomFieldModelValueDto`) :

```php
$this->mockCustomFieldModelValueApiData([
    'config' => $this->mockCustomFieldModelValueConfigApiData(['type' => 'unknown']),
]);
```
- Une réponse dont la forme n'a pas de DTO dédié (stats, `check-pending`) peut être écrite directement dans le test.

Les ids viennent de `TestCase::unique()`, un compteur remis à zéro avant chaque test : les ids d'un test sont donc toujours les mêmes, quel que soit l'ordre d'exécution. Un id que le test vérifie est quand même passé en override (`['id' => 42]`), pour que le test se lise sans connaître l'ordre des appels à `unique()`.

### Respecter le format réel de l'API

Les données mockées doivent ressembler à ce que l'API renvoie vraiment, sinon le test passe alors que le SDK casserait en production. En cas de doute, lisez le transformer correspondant dans le repo `origami_api` (`app/Api/Core/Transformers/...`) avant d'écrire les données. Points connus :

- **L'API est en PHP : une map vide est encodée `[]`**, pas `{}` (ex : aucun feature flag). Un test qui couvre ce cas utilise `jsonResponse([])`.
- Inversement, pour obtenir `{}` dans la réponse, passez `(object) []` : `'params' => (object) []`.
- Un tableau associatif est encodé en objet JSON et redevient un `stdClass` côté SDK. Si une donnée doit arriver en objet, utilisez un tableau associatif, et ajoutez un commentaire quand ce n'est pas évident :

```php
// Associative (not a list) : encoded as a JSON object, so it decodes back into a stdClass
// to match ModuleDto's typed `object` properties.
'configurations' => ['is_enabled' => true],
```

## `mock<Dto>Dto()` : le DTO construit

Quand un test a besoin du DTO lui-même (tests de DTO, client authentifié…), on ajoute à côté une méthode qui le construit à partir des données brutes :

```php
protected function mockSellerDto(array $override = []): SellerDto
{
    return new SellerDto(Obj::fromArray($this->mockSellerApiData($override)));
}
```

`Obj::fromArray()` convertit le tableau en objets, comme le ferait le décodage de la réponse JSON.

### Tester une clé absente

Un override ne peut pas retirer une clé (`array_merge` ajoute ou remplace, il ne supprime pas). Pour tester une réponse à laquelle il manque une clé obligatoire, on part des données brutes, on retire la clé avec `unset()`, puis on construit le DTO directement :

```php
public function test_value_case_3(): void
{
    $apiData = $this->mockCustomFieldModelValueApiData();
    unset($apiData['value']);

    $this->assertThatExceptionWasThrown(
        fn () => new CustomFieldModelValueDto(Obj::fromArray($apiData)),
        CustomFieldModelValueDtoNotConstructableException::class,
    );
}
```

C'est le seul cas où un test construit lui-même un DTO au lieu de passer par `mock<Dto>Dto()`.

## Réponses génériques

Dans `TestCase` et `TestsErrorDtos` :

| Helper | Pour |
|---|---|
| `mockApiPaginatedListData(array $items)` | Liste paginée sur une seule page (`data` + `meta.pagination`) |
| `mockApiPagination(int $count)` | La partie `pagination` seule |
| `mockApiErrorData($status, $detail, $code, ?$field)` | Une erreur de la liste `errors` |
| `mockApiErrorsResponse($status, ...$errors)` | Une réponse d'erreur complète |
