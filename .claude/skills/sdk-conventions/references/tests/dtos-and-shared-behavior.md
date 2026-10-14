# Tests — DTO, traits et comportements partagés

## Tests d'un DTO

Le parcours normal d'un DTO (lecture de la réponse d'un endpoint) est déjà couvert par les tests du repository qui le renvoie. On écrit un test de DTO dédié (`tests/Unit/Dtos/<Domaine>/<Dto>Test.php`) pour ce que les tests de repository ne montrent pas :

- les **includes** (présent, absent, mal formé) ;
- les **valeurs acceptées** d'un champ validé (chaque cas d'un enum que l'API peut envoyer) ;
- les **cas d'erreur** : une donnée invalide doit rendre le DTO non constructible.

Le DTO se construit avec `mock<Dto>Dto()` (voir `mock-data.md`), en passant par override ce que le cas met en jeu :

```php
/**
 * The `roles` include should be read as a collection of roles.
 */
public function test_roles_case_1(): void
{
    $userDto = $this->mockUserDto(['roles' => ['data' => [
        $this->mockRoleApiData(['id' => 1]),
        $this->mockRoleApiData(['id' => 2]),
    ]]]);

    $this->assertContainsOnlyInstancesOf(RoleDto::class, $userDto->roles);
    $this->assertSame([1, 2], $userDto->roles->pluck('id')->all());
}

/**
 * An include not wrapped in a `data` key should make the user not constructable.
 */
public function test_roles_case_3(): void
{
    $this->assertThatExceptionWasThrown(
        fn () => $this->mockUserDto(['roles' => [$this->mockRoleApiData()]]),
        UserDtoNotConstructableException::class,
    );
}
```

Ici, le test a deux temps seulement (construction, vérifications), puisque la construction est l'action testée.

## Tests de garde d'une convention

Quand un test protège une convention plutôt qu'un comportement précis, la classe porte un docblock qui explique la convention et ce qui casse si on ne la respecte pas :

```php
/**
 * Guards that `SellerDto` accepts every legacy seller state the central API can send in a seller's `state` field
 * (`seller_information.state`). `SellerDto` validates it against `SellerDtoStateEnum`: a missing case makes the
 * Dto — and the whole `SellerListDto` holding it — not constructable.
 */
class SellerDtoTest extends TestCase
```

C'est le cas de `ApiResponseDtoTest` (les clés de `toArray()` restent en camelCase, vérifiées avec `assertArrayKeysAreCamelCase()`).

## Comportement commun : passer par une fixture

Un trait ou une classe abstraite se teste **à travers une fixture** (`tests/Fixtures/`) : une classe minimale qui n'ajoute rien au comportement testé. Tester à travers un DTO ou un repository concret mélangerait leur comportement propre au test, et le test casserait si cette classe changeait.

La classe de test le dit dans son docblock :

```php
/**
 * Behavior shared by every Dto with includes, tested through a fixture (which adds nothing to it).
 */
class HasIncludesTest extends TestCase
```

### Fixture d'une classe à méthodes protégées

On élargit la visibilité de la méthode : en la redéfinissant si elle vient de la classe parente, avec `use LeTrait { methode as public; }` si elle vient d'un trait utilisé par la fixture (les deux cas sont détaillés dans `structure.md`, section « Rendre testable une méthode `protected` »). On instancie ensuite la fixture dans `setUp()` si tous les tests l'utilisent :

```php
private RestApiRepositoryFixture $repository;

protected function setUp(): void
{
    parent::setUp();

    $this->repository = new RestApiRepositoryFixture($this->mockGuestRestClientReturning());
}
```

`parent::setUp()` doit être appelé : c'est lui qui remet à zéro le compteur de `TestCase::unique()`.

### Fixture d'un DTO

Une fixture de DTO étend `ApiResponseDto`, utilise le trait testé, ne déclare que les propriétés utiles au test, et renvoie une exception anonyme comme exception « NotConstructable » :

```php
class IncludesDtoFixture extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    /** @var Collection<int, IncludesDtoFixture> */
    public Collection $children;

    public static function getAvailableIncludes(): array
    {
        return [
            'children' => self::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'       => 'id',
            'children' => fn (object $children) => $this->children = $this->collectIncluded($children, self::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id' => ['required', 'integer'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new class($msg, previous: $previous) extends ApiResponseDtoNotConstructableException {};
    }
}
```

Une fixture peut s'inclure elle-même (`self::class`) pour tester des includes imbriqués sans dépendre d'un autre DTO. Les tests la construisent avec un petit helper privé :

```php
private function makeFixture(array $apiData): IncludesDtoFixture
{
    return new IncludesDtoFixture(Obj::fromArray($apiData));
}
```

Un helper privé propre à une seule classe de test est placé à la fin de cette classe.

### Fixtures liées entre elles

Une fixture peut s'appuyer sur une autre fixture quand le comportement testé relie deux classes. Par exemple, le trait `HasIncludes` des param bags ne garde que les includes que le DTO renvoyé par la requête déclare disponibles. `IncludesParamBagFixture` renvoie donc `IncludesDtoFixture`, déjà écrite pour tester le trait `HasIncludes` des DTO :

```php
class IncludesParamBagFixture extends RequestParamBag
{
    use HasIncludes;

    // ...

    protected static function getRequestMainDto(): string
    {
        return IncludesDtoFixture::class;
    }
}
```

Réutilisez une fixture existante plutôt que d'en écrire une seconde qui ferait la même chose.

### Faire varier une fixture pour un seul test

Quand un seul test a besoin d'une variante de la fixture, on crée dans ce test une **classe anonyme** qui l'étend et redéfinit le strict nécessaire, plutôt qu'une nouvelle fixture :

```php
/**
 * When the Dto returned by the request declares no includes, every include should be ignored.
 */
public function test_set_includes_case_4(): void
{
    $paramBag = new class extends IncludesParamBagFixture
    {
        protected static function getRequestMainDto(): string
        {
            return FormTranslationDto::class;
        }
    };

    $paramBag->setIncludes(['children']);

    $this->assertSame([], $paramBag->getIncludes());
}
```

Si plusieurs tests ont besoin de la même variante, écrivez une vraie fixture dans `tests/Fixtures/`.
