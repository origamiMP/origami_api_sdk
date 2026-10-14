---
name: sdk-conventions
description: "Conventions de code du SDK PHP de l'API Origami (origami_api_sdk / origami-mp/origami-api-sdk). Documente les tests unitaires (PHPUnit) : arborescence et nommage, structure d'un test, assertions, données mockées (traits Tests<Domaine>Dtos), faux client HTTP (TestsRestClient), exceptions (assertThatExceptionWasThrown), fixtures et data providers ; et, pour le code, la gestion des includes dans les param bags et les DTO, et les param bags optionnels des méthodes de repository (le reste du code n'est pas encore documenté). À utiliser dès qu'on travaille sur ce SDK : quand on crée ou modifie un param bag, une méthode de repository ou un DTO, et en particulier dès qu'on écrit, complète, corrige ou relit un test — tests d'un repository (Data, Guest, Oauth), d'un DTO, d'un client REST, d'un trait ou d'un comportement partagé — même si l'utilisateur dit seulement « ajoute les TUs », « teste ce repo » ou « couvre ce code », et avant d'ajouter un helper ou des données de test dans tests/TestUtils."
---

# Conventions — SDK de l'API Origami

## Contexte du projet

- SDK PHP (`origami-mp/origami-api-sdk`) qui appelle l'API centrale Origami. Il est consommé notamment par origami_admin_v2.
- Organisation du code : des **repositories** (un appel HTTP en entrée, un DTO en sortie) répartis entre `Data` (authentifié), `Guest` (public) et `Oauth`, des **param bags** (les paramètres envoyés), des **DTO** (lecture et validation de la réponse de l'API), des **clients REST**, et des **traits** qui portent un comportement commun.
- Le code de l'API (pour vérifier le format réel de ses réponses) est dans le repo voisin `origami_api`.

## Comment utiliser ce skill

Le skill est découpé par domaine, chacun dans son sous-dossier de `references/`.

### Tests (documenté)

Commencez toujours par `references/tests/README.md` (les règles essentielles), puis lisez le fichier du type de test que vous écrivez :

| Fichier | À consulter quand… |
|---|---|
| `references/tests/README.md` | **Toujours, en premier** : ce qu'on teste, les règles essentielles |
| `references/tests/structure.md` | Vous créez un fichier de test, une fixture ou un trait de helpers : où le ranger, comment le nommer, comment l'enregistrer dans `TestCase` |
| `references/tests/writing-tests.md` | Vous écrivez n'importe quel test : nommage des méthodes, docblocks, découpage du corps, assertions, exceptions, data providers, valeurs utilisées |
| `references/tests/repositories.md` | Vous testez un repository (`Repositories/Api/...`) ou un client REST : faux client HTTP, ce qu'il faut vérifier, couverture attendue, exemples par type d'endpoint |
| `references/tests/mock-data.md` | Vous avez besoin de données de réponse de l'API ou d'un DTO tout fait : traits `Tests<Domaine>Dtos`, méthodes `mock…ApiData()` et `mock…Dto()`, format réel de l'API |
| `references/tests/dtos-and-shared-behavior.md` | Vous testez un DTO, un trait, une classe abstraite ou une convention transverse : fixtures, tests de garde |

En cas de doute, lisez plusieurs fichiers : ils sont courts et se complètent. Par exemple, un test de repository touche `repositories.md`, `writing-tests.md` et `mock-data.md`.

### Code du SDK (en cours de documentation)

| Fichier | À consulter quand… |
|---|---|
| `references/code/param-bags.md` | Vous créez ou modifiez un param bag, ou une méthode de repository qui appelle l'API : la règle des includes et sa portée (seul point documenté pour l'instant) |
| `references/code/repositories.md` | Vous créez ou modifiez une méthode de repository : quand son param bag peut être optionnel (seul point documenté pour l'instant) |
| `references/code/dtos.md` | Vous créez ou modifiez un DTO : déclaration des includes (y compris en commentaire), DTO de liste (seul point documenté pour l'instant) |

🚧 TODO — le reste des conventions du code (clients REST, exceptions, enums… et le reste des repositories, param bags et DTO) n'est pas encore écrit. En attendant :
- n'inventez pas de convention ;
- suivez le pattern le plus proche déjà présent dans `src/`, et signalez à l'utilisateur que le point n'est pas documenté.

## Règles générales

- Préférez les vérifications explicites (`is_null($x)`, `array_is_list($x)`) à une logique implicite justifiée par un commentaire.
- Le style est vérifié par Pint (`pint.json`, preset Laravel, `=>` alignés).
- Quand une convention n'est pas couverte ici, suivez le pattern le plus proche dans le code existant et signalez-le à l'utilisateur.

## Comment contribuer

Ce skill est versionné avec le code du SDK. Toute modification passe par une PR, comme pour du code.

- **Éditer le bon fichier** : chaque fichier de `references/` couvre un thème. Modifiez celui qui est concerné plutôt que de dupliquer une règle.
- **Résoudre un `🚧 TODO`** : remplacez le TODO par la règle définitive, dans le même fichier.
- **Ajouter un fichier ou un domaine** : un nouveau type de code (ex : les enums) va dans `references/code/`, un domaine entièrement nouveau dans son propre sous-dossier de `references/`. Ajoutez chaque nouveau fichier dans les tables de ce SKILL.md, sinon il risque de ne jamais être lu.
- **Changer le périmètre** : relisez et mettez à jour la `description` du frontmatter. C'est elle qui décide quand Claude consulte le skill.
