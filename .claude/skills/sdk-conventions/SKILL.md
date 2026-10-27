---
name: sdk-conventions
description: "Use when working in origami_api_sdk: creating or changing a repository method, a param bag or a DTO, or writing, fixing or reviewing PHPUnit tests (« ajoute les TUs », \"add tests\", \"cover this code\")."
---

# Conventions — Origami API SDK

## Project context

- PHP SDK (`origami-mp/origami-api-sdk`) calling the central Origami API. It is used by origami_admin_v2, among others.
- Code organization: **repositories** (an HTTP call in, a DTO out) split between `Data` (authenticated), `Guest` (public) and `Oauth`, **param bags** (the parameters sent), **DTOs** (reading and validating the API response), **REST clients**, and **traits** carrying shared behavior.
- The API code (to check the actual format of its responses) lives in the sibling repo `origami_api`.

## How to use this skill

The skill is split by domain, each in its own subfolder of `references/`.

### Tests (documented)

Start with `references/tests/README.md`: it lists every test topic and the single file where its rule is written.

### SDK code (being documented)

| File | Read it when… |
|---|---|
| `references/code/param-bags.md` | You create or change a param bag, or a repository method calling the API: the API key declared for every property sent and its custom cast, and the include rule and its scope |
| `references/code/repositories.md` | You create or change a repository method: when its param bag can be optional (only documented topic so far) |
| `references/code/dtos.md` | You create or change a DTO: declaring includes (commented ones included), list DTOs (only documented topic so far) |

🚧 TODO — the rest of the code conventions (REST clients, exceptions, enums… and the rest of repositories, param bags and DTOs) is not written yet (see the last general rule).

## General rules

- Prefer explicit checks (`is_null($x)`, `array_is_list($x)`) over implicit logic explained by a comment.
- Code style is checked by Pint (`pint.json`, Laravel preset, aligned `=>`).
- When a convention is not covered here, do not invent one: follow the closest pattern in the existing code and tests, and tell the user the point is not documented.

## How to contribute

This skill is versioned with the SDK code. Any change goes through a PR, like code.

- **One rule, one place**: each rule is written in a single file; other files refer to it instead of rephrasing it, so that two copies never contradict each other.
- **Refer to real code**: point to existing tests and classes by name (`OrigamiTaxDataApiRepositoryTest::test_create_case_1`) and keep only short excerpts, rather than copying code that will diverge.
- **Resolve a `🚧 TODO`**: replace the TODO with the final rule, in the same file.
- **Add a file or a domain**: a new kind of code (e.g. enums) goes in `references/code/`, an entirely new domain in its own subfolder of `references/`. Add every new file to its index (`references/tests/README.md` for tests, the table above for code), otherwise it may never be read.
- **Change the scope**: reread and update the frontmatter `description`. It is what decides when Claude reads the skill.
