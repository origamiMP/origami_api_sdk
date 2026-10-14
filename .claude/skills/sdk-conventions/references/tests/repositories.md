# Tests — Repositories et clients REST

Un test de repository vérifie les deux côtés de l'appel : **la requête HTTP que le SDK envoie** (verbe, chemin, query, body) et **le DTO qu'il construit** à partir de la réponse. Les deux sont du contrat public du SDK : une erreur sur l'un ou l'autre casse les applications qui l'utilisent.

## Le faux client HTTP (`TestsRestClient`)

Aucun test n'appelle le réseau et aucun n'utilise `createMock()` ou Mockery. On utilise un **vrai** client REST du SDK, dont le client Guzzle répond avec des réponses mises en file. Toute la logique du client (headers, préfixe `v1/`, gestion d'erreurs) s'exécute donc vraiment.

| Helper | Rôle |
|---|---|
| `mockDataRestClientReturning(...$responses)` | Client authentifié (repositories `Data`) |
| `mockGuestRestClientReturning(...$responses)` | Client public (repositories `Guest`) |
| `mockOauthRestClientReturning(...$responses)` | Client Oauth (pas de préfixe `v1/`) |
| `jsonResponse(array $body, int $status = 200)` | Réponse JSON |
| `emptyResponse(int $status = 204)` | Réponse sans body (actions qui ne renvoient rien) |
| `new Response($status, [], $rawBody)` | Body brut (HTML, JSON invalide, `{}`…) |
| un `\Throwable` à la place d'une réponse | Panne réseau (ex : `ConnectException`) |
| `useMockedGuzzleClient($restClient, [$responses])` | Pour un client construit à la main (user agent personnalisé, contexte de user group…) |

Choisissez le client qui correspond au repository : un repository `Data` reçoit `mockDataRestClientReturning()`, un repository `Guest` `mockGuestRestClientReturning()`.

## Ce qu'il faut vérifier, dans cet ordre

1. **La requête** : `assertLastApiRequestWas('GET', 'sellers/42')`. Le chemin s'écrit sans `v1/` ; pour Oauth, on passe `prefix: ''`.
2. **Ce qu'elle transporte** :
   - `assertLastApiRequestQueryWas([...])` pour la query. Les valeurs y sont **toujours des chaînes** (`'page' => '2'`, un booléen `true` devient `'1'`).
   - `assertLastApiRequestJsonWas([...])` pour le body JSON.
   - `assertLastApiRequestHadNoBody()` quand la requête ne doit rien envoyer (`delete`, `activate`, `duplicate`…).
3. **Le DTO** : `assertInstanceOf(XxxDto::class, $result)`.
4. **Ses valeurs** : au minimum l'id (ou la clé qui identifie l'objet), puis les valeurs que le cas met en jeu (enums, dates, objets imbriqués…).

### Le body attendu s'écrit avec les valeurs réelles de l'API

Le JSON attendu documente le format envoyé à l'API : écrivez les clés en snake_case et les **valeurs littérales**, pas les enums ni les objets du SDK.

```php
$paramBag->sellerType = CreateSellerParamBagTypeEnum::MARKETPLACE;
$paramBag->holidayDepartureDate = Carbon::parse('2024-07-01 08:00:00');
// ...
$this->assertLastApiRequestJsonWas([
    'seller_type'            => 'marketplace',
    'holiday_departure_date' => '2024-07-01',
    // ...
]);
```

Une propriété de param bag non définie n'est pas envoyée. Si un cas teste ça (mise à jour partielle, nœud sans `id` à créer), dites-le dans le docblock et vérifiez que la clé est absente du JSON attendu.

## Couverture attendue

- **Au moins un test par méthode publique** du repository.
- **Un cas en plus par variation notable**, par exemple :
  - un filtre non disponible sur l'endpoint, qui ne doit pas être envoyé ;
  - un paramètre optionnel non défini (`with_count` absent → `fieldsCount` à `null`) ;
  - une réponse vide ou une forme particulière de réponse (`[]` pour une map vide, objet sans clé `data`) ;
  - les valeurs par défaut du param bag face à celles qu'on définit (client id et secret Oauth).
- **Un test d'include par méthode qui en accepte** (voir ci-dessous).
- Inutile de retester dans chaque repository ce que les tests du client ou de `RestApiRepository` couvrent déjà (codes HTTP d'erreur, réponse illisible).

### Les includes : à vérifier pour chaque méthode qui en accepte

Les includes sont facilement oubliés, parce que le premier test d'une méthode ne les utilise pas. Ils relient pourtant trois pièces qui peuvent casser séparément : le param bag (qui filtre et envoie les includes), le DTO renvoyé (qui déclare ceux qui sont disponibles) et sa lecture de la réponse.

**Quand une méthode en accepte-t-elle ?** Son param bag utilise le trait `HasIncludes`, **et** le DTO renvoyé par son `getRequestMainDto()` déclare au moins un include dans `getAvailableIncludes()`. Si cette liste est vide (ou entièrement commentée), `setIncludes()` retire tout : il n'y a rien à tester tant qu'un include n'y est pas ajouté.

**Vérifiez aussi l'inverse** : d'après `references/code/param-bags.md`, toute requête de l'API qui propose un include doit pouvoir le recevoir depuis le SDK. Si l'API propose des includes pour l'endpoint testé (transformer ou contrôleur) mais que la méthode ne prend pas de param bag avec `HasIncludes`, ou que le DTO ne déclare pas l'un de ces includes (même en commentaire), le code n'est pas conforme : signalez-le à l'utilisateur plutôt que d'écrire un test qui fige ce manque.

**Le test ajoute un cas à la méthode** (`test_<méthode>_case_<n>`), qui :
1. demande les includes disponibles **plus un include inconnu** avec `setIncludes()` ;
2. mocke une réponse qui contient l'include, enveloppé comme le fait Fractal (`['data' => …]`) ;
3. vérifie que la query `include` ne contient que les includes disponibles ;
4. vérifie que l'include est lu : type du DTO inclus et son id.

```php
/**
 * `GET users/groups/invitations/history` should send the includes available on an invitation as query, and read
 * the included user group of each invitation.
 */
public function test_get_history_case_2(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
        $this->mockUserGroupInvitationApiData(['user_group' => ['data' => $this->mockUserGroupApiData(['id' => 42])]]),
    ]]));
    $paramBag = new GetUserGroupInvitationHistoryRequestParamBag;
    $paramBag->email = 'john@doe.com';
    $paramBag->setIncludes(['user_group', 'unknown']);

    $history = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->getHistory($paramBag);

    $this->assertLastApiRequestWas('GET', 'users/groups/invitations/history');
    $this->assertLastApiRequestQueryWas(['email' => 'john@doe.com', 'include' => ['user_group']]);
    $this->assertInstanceOf(UserGroupInvitationHistoryDto::class, $history);
    $this->assertInstanceOf(UserGroupDto::class, $history->data->first()->userGroup);
    $this->assertSame(42, $history->data->first()->userGroup->id);
}
```

Le filtrage lui-même (doublons, includes imbriqués, DTO sans include) est testé une fois pour toutes dans `Traits/ParamBags/HasIncludesTest`. Le test du repository vérifie seulement que la méthode est bien reliée au bon DTO.

Quand un include est ajouté (ou décommenté) dans le `getAvailableIncludes()` d'un DTO, ajoutez le test d'include de chaque méthode qui renvoie ce DTO.

## Exemples par type d'endpoint

### Liste paginée avec filtres

```php
/**
 * `GET sellers` should send the filters, pagination and search params as query, and read the paginated list
 * answered.
 */
public function test_list_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
        $this->mockSellerApiData(['id' => 1]),
        $this->mockSellerApiData(['id' => 2]),
    ])));
    $paramBag = new ListSellerRequestParamBag;
    $paramBag->withFilters(['address_city' => 'Paris']);
    $paramBag->page(3);
    $paramBag->search('john');

    $sellers = (new OrigamiSellerDataApiRepository($restClient))->list($paramBag);

    $this->assertLastApiRequestWas('GET', 'sellers');
    $this->assertLastApiRequestQueryWas([
        'filter'        => ['address_city' => 'Paris'],
        'page'          => '3',
        'item_per_page' => '20',
        'search'        => 'john',
    ]);
    $this->assertInstanceOf(SellerListDto::class, $sellers);
    $this->assertSame([1, 2], $sellers->data->pluck('id')->all());
}
```

Une liste non paginée se mocke avec `['data' => [...]]`, et le docblock le dit : « answers a non-paginated list: each of its items should be read as a module ».

### Lecture d'un élément (clé `data`)

Voir l'exemple de `test_get_case_1` dans `writing-tests.md`. Le docblock précise « read … from the `data` key of the response ».

### Création (body JSON + réponse)

```php
/**
 * `POST taxes` should send the tax as JSON, its translations included, and read the created one from the
 * `data` key of the response.
 */
public function test_create_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockTaxApiData([
        'id'    => 42,
        'value' => 5.5,
    ])]));
    $translation = new TaxTranslationParamBag;
    $translation->languageId = 1;
    $translation->name = 'Reduced VAT';
    $paramBag = new CreateTaxRequestParamBag;
    $paramBag->type = TaxDtoTypeEnum::PERCENTAGE;
    $paramBag->value = 5.5;
    $paramBag->isDefault = false;
    $paramBag->translations = [$translation];

    $tax = (new OrigamiTaxDataApiRepository($restClient))->create($paramBag);

    $this->assertLastApiRequestWas('POST', 'taxes');
    $this->assertLastApiRequestJsonWas([
        'type'         => 'percentage',
        'value'        => 5.5,
        'is_default'   => false,
        'translations' => [
            ['language_id' => 1, 'name' => 'Reduced VAT'],
        ],
    ]);
    $this->assertInstanceOf(TaxDto::class, $tax);
    $this->assertSame(42, $tax->id);
    $this->assertSame(5.5, $tax->value);
}
```

Pour un arbre de param bags volumineux, une ligne vide sépare la construction de chaque nœud.

### Mise à jour ou action sans réponse (204)

```php
/**
 * `PATCH taxes/{id}` should send only the properties set on the param bag as JSON.
 */
public function test_update_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->emptyResponse());
    $paramBag = new UpdateTaxRequestParamBag;
    $paramBag->value = 10.0;

    (new OrigamiTaxDataApiRepository($restClient))->update(42, $paramBag);

    $this->assertLastApiRequestWas('PATCH', 'taxes/42');
    $this->assertLastApiRequestJsonWas(['value' => 10.0]);
}

public function test_delete_case_1(): void
{
    $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

    (new OrigamiTaxDataApiRepository($restClient))->delete(42);

    $this->assertLastApiRequestWas('DELETE', 'taxes/42');
    $this->assertLastApiRequestHadNoBody();
}
```

## Tests des clients REST

Le comportement commun à tous les clients (headers, préfixe d'URL, conversion des erreurs en exceptions) est testé dans `OrigamiRestClientTest`, à travers le client Guest. Les tests propres à un client (header `Authorization` et `context` du client Data, absence de préfixe du client Oauth) sont dans le fichier de ce client. On y appelle directement `$restClient->get('some_resource')`, et on lit la requête avec `$this->lastApiRequest()` :

```php
$this->assertSame('Bearer some-access-token', $this->lastApiRequest()->getHeaderLine('Authorization'));
```

Les réponses d'erreur de l'API se construisent avec `TestsErrorDtos` :

```php
$restClient = $this->mockGuestRestClientReturning($this->mockApiErrorsResponse(
    422,
    $this->mockApiErrorData(422, 'Email is invalid.', '999999', 'email'),
    $this->mockApiErrorData(422, 'Name is required.', '999999', 'name'),
));
```
