---
name: sdk-conventions
description: "Code conventions of the Origami API PHP SDK (origami_api_sdk / origami-mp/origami-api-sdk). Documents unit tests (PHPUnit): file layout and naming, test structure, assertions, mock data (Tests<Domain>Dtos traits), fake HTTP client (TestsRestClient), exceptions (assertThatExceptionWasThrown), fixtures and data providers; and, for the code, include handling in param bags and DTOs, and optional param bags of repository methods (the rest of the code is not documented yet). Use it whenever working on this SDK: when creating or changing a param bag, a repository method or a DTO, and above all whenever writing, completing, fixing or reviewing a test — tests of a repository (Data, Guest, Oauth), a DTO, a REST client, a trait or a shared behavior — even if the user only says « ajoute les TUs », « add tests », « test this repo » or « cover this code », and before adding a helper or test data in tests/TestUtils."
---

# Conventions — Origami API SDK

## Project context

- PHP SDK (`origami-mp/origami-api-sdk`) calling the central Origami API. It is used by origami_admin_v2, among others.
- Code organization: **repositories** (an HTTP call in, a DTO out) split between `Data` (authenticated), `Guest` (public) and `Oauth`, **param bags** (the parameters sent), **DTOs** (reading and validating the API response), **REST clients**, and **traits** carrying shared behavior.
- The API code (to check the actual format of its responses) lives in the sibling repo `origami_api`.

## How to use this skill

The skill is split by domain, each in its own subfolder of `references/`.

### Tests (documented)

Always start with `references/tests/README.md` (the essential rules), then read the file matching the kind of test you are writing:

| File | Read it when… |
|---|---|
| `references/tests/README.md` | **Always, first**: what we test, the essential rules |
| `references/tests/structure.md` | You create a test file, a fixture or a helper trait: where to put it, how to name it, how to register it in `TestCase` |
| `references/tests/writing-tests.md` | You write any test: method naming, docblocks, test body layout, assertions, exceptions, data providers, values used |
| `references/tests/repositories.md` | You test a repository (`Repositories/Api/...`) or a REST client: fake HTTP client, what to check, expected coverage, examples per endpoint type |
| `references/tests/mock-data.md` | You need API response data or a ready-made DTO: `Tests<Domain>Dtos` traits, `mock…ApiData()` and `mock…Dto()` methods, actual API format |
| `references/tests/dtos-and-shared-behavior.md` | You test a DTO, a trait, an abstract class or a cross-cutting convention: fixtures, guard tests |

When in doubt, read several files: they are short and complement each other. For instance, a repository test involves `repositories.md`, `writing-tests.md` and `mock-data.md`.

### SDK code (being documented)

| File | Read it when… |
|---|---|
| `references/code/param-bags.md` | You create or change a param bag, or a repository method calling the API: the include rule and its scope (only documented topic so far) |
| `references/code/repositories.md` | You create or change a repository method: when its param bag can be optional (only documented topic so far) |
| `references/code/dtos.md` | You create or change a DTO: declaring includes (commented ones included), list DTOs (only documented topic so far) |

🚧 TODO — the rest of the code conventions (REST clients, exceptions, enums… and the rest of repositories, param bags and DTOs) is not written yet. In the meantime:
- do not invent a convention;
- follow the closest pattern already in `src/`, and tell the user the point is not documented.

## General rules

- Prefer explicit checks (`is_null($x)`, `array_is_list($x)`) over implicit logic explained by a comment.
- Code style is checked by Pint (`pint.json`, Laravel preset, aligned `=>`).
- When a convention is not covered here, follow the closest pattern in the existing code and tell the user.

## How to contribute

This skill is versioned with the SDK code. Any change goes through a PR, like code.

- **Edit the right file**: each file of `references/` covers one topic. Change the relevant one rather than duplicating a rule.
- **Resolve a `🚧 TODO`**: replace the TODO with the final rule, in the same file.
- **Add a file or a domain**: a new kind of code (e.g. enums) goes in `references/code/`, an entirely new domain in its own subfolder of `references/`. Add every new file to the tables of this SKILL.md, otherwise it may never be read.
- **Change the scope**: reread and update the frontmatter `description`. It is what decides when Claude reads the skill.
