# Tests — Arborescence et nommage des fichiers

## Arborescence

```
tests/
├── TestCase.php          # classe de base de tous les tests, utilise tous les traits de TestUtils
├── Fixtures/             # classes minimales pour tester un comportement commun
│   ├── RestApiRepositoryFixture.php
│   ├── IncludesDtoFixture.php
│   └── IncludesParamBagFixture.php   # …
├── TestUtils/            # traits de helpers et de données mockées
│   ├── TestsRestClient.php        # faux client HTTP + assertions sur la requête
│   ├── TestsExceptions.php        # assertThatExceptionWasThrown()
│   ├── TestsErrorDtos.php         # réponses d'erreur de l'API
│   ├── TestsArrayableDtos.php     # assertions sur toArray()
│   └── Tests<Domaine>Dtos.php     # données mockées d'un domaine (Seller, Form, User…)
└── Unit/                 # les tests, rangés comme src/
    ├── Dtos/User/UserDtoTest.php
    ├── Repositories/Api/Data/Seller/OrigamiSellerDataApiRepositoryTest.php
    ├── Repositories/Client/OrigamiRestClientTest.php
    ├── Traits/Dtos/HasIncludesTest.php
    └── Traits/ParamBags/HasIncludesTest.php
```

## Fichiers de test

- **Un fichier de test par classe testée**, à l'endroit qui correspond à la classe dans `src/` : `src/Repositories/Api/Data/Form/OrigamiFormDataApiRepository.php` est testé par `tests/Unit/Repositories/Api/Data/Form/OrigamiFormDataApiRepositoryTest.php`.
- La classe s'appelle `<ClasseTestée>Test`, dans le namespace `Tests\Unit\...` correspondant, et étend `Tests\TestCase` (jamais directement `PHPUnit\Framework\TestCase`, pour avoir tous les helpers).
- Un comportement commun à plusieurs classes est testé dans le fichier de la classe qui le porte : le comportement de tous les clients REST est dans `OrigamiRestClientTest`, celui de tous les repositories dans `RestApiRepositoryTest`, celui du trait `HasIncludes` dans `Traits/Dtos/HasIncludesTest`.

## Fixtures (`tests/Fixtures/`)

Une fixture est une classe minimale, écrite uniquement pour les tests, qui hérite d'une classe abstraite ou utilise un trait. Elle permet de tester un comportement général sans passer par une classe concrète du SDK, dont le comportement propre brouillerait le test.

- Nom : `<Sujet>Fixture` (ex : `RestApiRepositoryFixture`, `IncludesDtoFixture`), namespace `Tests\Fixtures`.
- Un docblock dit à quoi elle sert et ce qu'elle expose.
- Elle ne contient que ce qu'il faut pour le test.

### Rendre testable une méthode `protected`

La technique dépend de l'endroit où la méthode est définie :

| La méthode `protected` est définie… | Dans la fixture |
|---|---|
| dans la classe parente (ou dans un trait de la classe parente) | La redéfinir en `public` et appeler `parent::methode()` |
| dans un trait que la fixture utilise elle-même | `use LeTrait { methode as public; }` |

**Méthode de la classe parente** : on la redéfinit en `public`.

```php
/**
 * Minimal RestApiRepository, exposing its protected helpers to test the behavior shared by every repository.
 */
class RestApiRepositoryFixture extends RestApiRepository
{
    public function decodeResponse(ResponseInterface $response): object
    {
        return parent::decodeResponse($response);
    }
}
```

**Méthode d'un trait utilisé par la fixture** : on change sa visibilité au moment du `use`.

```php
class FiltersParamBagFixture extends RequestParamBag
{
    use HasFilters {
        getFiltersValidationRules as public;
    }

    // ...
}
```

La redéfinition ne marche pas dans ce second cas. PHP copie les méthodes d'un trait dans la classe qui l'utilise : la méthode redéfinie remplace celle du trait, et `parent::` la cherche dans la classe parente, où elle n'existe pas (`Call to undefined method`).

N'utilisez pas d'alias (`methode as public exposedMethode;`) : le test appellerait un nom qui n'existe pas dans le SDK.

Les détails (fixture de DTO, exception « NotConstructable ») sont dans `dtos-and-shared-behavior.md`.

## Traits de helpers (`tests/TestUtils/`)

- Tout helper réutilisable va dans un trait de `tests/TestUtils/`, namespace `Tests\TestUtils`, nommé `Tests<Sujet>` : `TestsExceptions`, `TestsRestClient`, `Tests<Domaine>Dtos`.
- On ajoute un helper à un trait existant si le sujet correspond, et on crée un trait sinon.
- Les méthodes sont `protected`. Les assertions s'appellent `assert…` (`assertLastApiRequestWas`, `assertThatExceptionWasThrown`, `assertArrayKeysAreCamelCase`), les données mockées `mock…`.
- Chaque helper dont le nom ne suffit pas a un docblock en anglais (ce qu'il fait, ses particularités, ex : « Query string values are always strings once sent »).

### Enregistrer un nouveau trait

Un nouveau trait doit être importé et utilisé dans `tests/TestCase.php`, **par ordre alphabétique**, dans les `use` en tête de fichier comme dans la liste `use` de la classe :

```php
use Tests\TestUtils\TestsErrorDtos;
use Tests\TestUtils\TestsExceptions;
use Tests\TestUtils\TestsFormDtos;
// ...

abstract class TestCase extends BaseTestCase
{
    use TestsArrayableDtos,
        TestsCountryDtos,
        // ...
        TestsFormDtos,
        // ...
```

`TestCase` ne contient en propre que les helpers vraiment transverses (`unique()`, `mockApiPagination()`, `mockApiPaginatedListData()`).
