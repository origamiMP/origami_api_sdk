# Tests — Écrire un test

## Nommage des méthodes

`test_<sujet>_case_<n>`, en snake_case :

- `<sujet>` est **la méthode testée**, en snake_case (`getLegalTypes()` → `test_get_legal_types_case_1`), ou, pour un comportement transverse, **le nom de ce comportement** (`test_headers_case_1`, `test_url_case_2`, `test_request_error_case_3`, `test_state_case_1`, `test_roles_case_2`).
- Quand l'effet testé ne s'observe qu'à travers une autre méthode, le sujet est **le nom de cet effet**, pas celui de la méthode appelée. Par exemple, l'envoi des includes en query se voit à travers `asGuzzleParams()` : le test s'appelle `test_query_case_1`, pas `test_as_guzzle_params_case_1`.
- `<n>` commence à 1 pour chaque sujet et se suit sans trou. Le numéro ne porte pas de sens : c'est le docblock qui décrit le cas.

On n'utilise ni l'attribut `#[Test]`, ni l'annotation `@test`, ni des noms descriptifs en camelCase.

## Docblock

Chaque test a un docblock **en anglais**, d'une à trois lignes, qui décrit le **comportement attendu** (pas les étapes du test), avec « should » :

```php
/**
 * `GET sellers` should send the filters, pagination and search params as query, and read the paginated list
 * answered.
 */
```

- Pour un endpoint, il commence par le verbe et le chemin entre backticks : `` `POST forms/{id}/duplicate` ``, `` `PATCH taxes/{id}` ``.
- Il mentionne les particularités qui justifient le cas : « A filter not available on `GET sellers` should not be sent. », « (no `v1/` prefix) », « answers a flat object (no `data` key) ».
- On peut l'omettre quand le nom et le corps suffisent, typiquement pour un appel sans body ni réponse (`test_delete_case_1`, `test_activate_case_1`) ou pour la variante triviale d'un cas déjà décrit (`test_collect_included_case_2` : la liste vide).
- Les lignes s'arrêtent vers 120 caractères.

La **classe** n'a de docblock que si elle teste à travers un intermédiaire ou si elle protège une convention :

```php
/**
 * Behavior shared by every Origami rest client, tested through the Guest one (which adds nothing to it).
 */
```

## Corps du test

Trois blocs séparés par une ligne vide : la préparation, l'appel, les vérifications.

```php
public function test_get_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerApiData(['id' => 42])]));

    $seller = (new OrigamiSellerDataApiRepository($restClient))->get(42);

    $this->assertLastApiRequestWas('GET', 'sellers/42');
    $this->assertInstanceOf(SellerDto::class, $seller);
    $this->assertSame(42, $seller->id);
}
```

- **Préparation** : d'abord le client (`$restClient = …`), puis les param bags. Un param bag est rempli propriété par propriété (`$paramBag->email = '…';`), dans l'ordre où l'API les attend. Pour un arbre de param bags, on construit les feuilles d'abord et la racine en dernier.
- **Appel** : la classe testée est instanciée dans la même ligne que l'appel, et le résultat est rangé dans une variable nommée d'après ce qu'il contient (`$seller`, `$forms`, `$invitation`, `$legalTypes`). `$result` est réservé aux DTO génériques (succès, message). Pas de variable si la méthode ne renvoie rien.
- **Vérifications** : d'abord la requête, puis la réponse (voir `repositories.md`).
- Une ligne vide peut séparer des groupes de vérifications quand on descend dans un objet imbriqué ; on extrait alors le nœud dans une variable (`$page = $form->pages->first();`).

Quand il n'y a pas d'action à part, le test n'a que **deux blocs** : la construction, puis les vérifications. C'est le cas quand la construction est elle-même l'action testée (un DTO construit à partir d'une réponse, voir `dtos-and-shared-behavior.md`), ou quand on vérifie l'état par défaut d'un objet neuf :

```php
public function test_get_includes_case_1(): void
{
    $paramBag = new IncludesParamBagFixture;

    $this->assertSame([], $paramBag->getIncludes());
}
```

## Assertions

- **`assertSame` par défaut**, pour les scalaires, les tableaux et les enums : `$this->assertSame(FormFieldDtoTypeEnum::SELECT, $field->type);`.
- **`assertEquals`** seulement pour comparer des objets dont l'identité n'a pas d'importance (`stdClass` décodé : `assertEquals((object) [], $responseContent)`).
- `assertTrue` / `assertFalse` / `assertNull` pour les booléens et `null`.
- `assertInstanceOf` pour le type du DTO renvoyé, `assertContainsOnlyInstancesOf` pour le contenu d'une collection.
- **Pour une liste**, on compare une projection plutôt que chaque élément : `$this->assertSame([1, 2], $sellers->data->pluck('id')->all());`. Ça vérifie à la fois le nombre, l'ordre et le type de chaque élément lu.
- Une propriété non définie (include absent) se vérifie avec `$this->assertFalse(isset($userDto->roles));`.

## Exceptions

Toujours `assertThatExceptionWasThrown()` (trait `TestsExceptions`). Il vérifie que l'exception est levée, sa classe et, si on le donne, son message exact. Il renvoie l'exception pour les vérifications suivantes :

```php
$exception = $this->assertThatExceptionWasThrown(
    fn () => $this->repository->decodeResponse(new Response(200, [], $body)),
    OrigamiApiUnknownException::class,
    'Unreadable response from Origami API : HTTP 200, the body is not valid JSON',
);

$this->assertInstanceOf(\JsonException::class, $exception->getPrevious());
```

Quand il tient sur une ligne : `$this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), HttpClientException::class);`.

N'utilisez ni `expectException()` (il arrête le test au moment de l'exception, on ne peut plus rien vérifier ensuite) ni un `try` / `$this->fail()` / `catch` écrit à la main.

## Data providers

Quand un même comportement doit être vérifié pour plusieurs entrées :

```php
#[DataProvider('invalidJsonBodyProvider')]
public function test_decode_response_case_3(string $body): void
{
    // ...
}

public static function invalidJsonBodyProvider(): array
{
    return [
        'html page'  => ['<html>Bad Gateway</html>'],
        'empty body' => [''],
        'truncated'  => ['{"data": {"id": 4'],
    ];
}
```

- L'attribut `#[DataProvider('…')]` (PHPUnit 12), jamais l'annotation `@dataProvider`.
- Méthode `public static`, nommée `<ceQuiVarie>Provider`.
- **Clés lisibles** en anglais (`'bad request'`, `'empty object'`) : c'est ce qu'affiche PHPUnit en cas d'échec.
- Placée **juste après le test** qui l'utilise, ou **en fin de classe** si plusieurs tests la partagent.
- Si un paramètre est une classe, typez-le en docblock : `@param  class-string<OrigamiApiSingleException>  $expectedExceptionClass`.

## Valeurs utilisées

Les tests réutilisent les mêmes valeurs, pour qu'on reconnaisse tout de suite ce qui est une donnée de test :

| Besoin | Valeur |
|---|---|
| Id vérifié par le test | `42` (puis `43`, ou `1`, `2`, `3` pour une liste) |
| Id non vérifié | `TestCase::unique()` (dans les traits de données) |
| Email | `john@doe.com`, `jane@doe.com` |
| Personne | `John` / `Doe` |
| Token, secret | `some-token`, `some-access-token`, `some-refresh-token` |
| Ressource générique (tests de client) | `some_resource` |
| URL de l'API | `http://api.test` (constante `TEST_API_BASE_URI`) |
| URL externe | `https://example.com/...` |
| Date | `'2024-05-12 16:12:56'` (timestamps des données mockées) |

Les valeurs métier restent réalistes (`'10 rue de Rivoli'`, `'FR7630006000011234567890189'`, `'Quote request'`) : elles servent aussi de documentation.
