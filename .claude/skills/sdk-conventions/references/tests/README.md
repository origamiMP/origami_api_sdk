# Tests — Règles essentielles

À lire avant d'écrire ou de relire n'importe quel test du SDK. Le détail de chaque règle est dans les autres fichiers de ce dossier (voir la table dans `SKILL.md`).

## Ce qu'on teste, et comment

- Ce qu'on teste : les **repositories** (un appel HTTP en entrée, un DTO en sortie), les **DTO** (lecture et validation de la réponse de l'API), les **clients REST** (headers, URL, erreurs) et les **traits ou classes abstraites** qui portent un comportement commun.
- Seuls des tests unitaires existent (`tests/Unit`, PHPUnit 12, PHP 8.4).
- Les tests sont **sans réseau et sans librairie de mock** : un vrai client REST du SDK tourne contre une fausse API (des réponses Guzzle mises en file), puis on vérifie la requête partie et le DTO obtenu.

## Les règles

1. **Un test = une méthode `test_<sujet>_case_<n>`** (snake_case, `n` à partir de 1 pour chaque sujet). Le sujet est la méthode testée (`test_get_legal_types_case_1`) ou le comportement transverse (`test_headers_case_2`).
2. **Un docblock en anglais au-dessus du test** décrit le comportement attendu avec « should ». Pour un endpoint, il commence par `` `VERBE chemin` ``. On ne l'omet que si le nom et le corps disent déjà tout (ex : un `delete` sans body).
3. **Préparation, appel, vérifications**, séparés par une ligne vide.
4. **Vérifier d'abord la requête, puis la réponse** : `assertLastApiRequestWas()`, puis query, JSON ou absence de body, puis `assertInstanceOf()` du DTO, puis ses valeurs.
5. **`assertSame` par défaut.** `assertEquals` seulement pour comparer des objets (`stdClass`).
6. **Les exceptions passent toujours par `assertThatExceptionWasThrown()`**, jamais par `expectException()` ni par un `try` / `fail` / `catch`.
7. **Les données de l'API viennent des traits `Tests<Domaine>Dtos`** (`mock…ApiData()`), avec un override limité aux valeurs que le test vérifie. On ne recopie pas une réponse complète dans le test.
8. **Les ids viennent de `TestCase::unique()`** (un compteur remis à zéro avant chaque test, donc des ids fixes). Un id que le test vérifie est donné en dur (`42`).
9. **Pas de réseau ni de librairie de mock** : un vrai client REST du SDK tourne contre des réponses Guzzle mises en file (`TestsRestClient`).
10. **Un comportement commun se teste à travers une fixture** (`tests/Fixtures/`), pas à travers une classe concrète choisie au hasard.
11. **Chaque méthode publique d'un repository a au moins un test**, plus un cas par variation notable (filtre ignoré, réponse vide…). **Chaque méthode qui accepte des includes a en plus un test d'include** (query `include` envoyée et include lu, voir `repositories.md`).
12. **Pour finir** : `vendor/bin/phpunit` doit passer, et `vendor/bin/pint --test` aussi.

## Écrire du code de test lisible

- Si un helper manque, ajoutez-le dans un trait de `tests/TestUtils/` plutôt que de répéter le code dans plusieurs tests (voir `structure.md`).
- Quand une convention n'est pas couverte ici, suivez le pattern le plus proche dans les tests existants et signalez à l'utilisateur que le point n'est pas documenté. N'inventez pas de nouvelle convention.
