---
name: sdk-conventions
description: "Use when working in origami_api_sdk: creating or changing an API repository method, a client repository, a param bag or a DTO, or writing, fixing or reviewing PHPUnit tests (« ajoute les TUs », \"add tests\", \"cover this code\")."
---

# Conventions — Origami API SDK

## Project context

- PHP SDK (`origami-mp/origami-api-sdk`) calling the central Origami API. It is used by origami_admin_v2, among others.
- Code organization: **API repositories** (an HTTP call in, a DTO out) split between `Data` (authenticated), `Guest` (public) and `Oauth`, **param bags** (the parameters sent), **DTOs** (reading and validating the API response), **client repositories** (sending the HTTP requests of the API repositories), and **traits** carrying shared behavior.
- The API code (to check the actual format of its responses) lives in the sibling repo `origami_api`.

## How to use this skill

The skill is split by domain, each in its own subfolder of `references/`.

### Tests (documented)

Start with `references/tests/README.md`: it lists every test topic and the single file where its rule is written.

### SDK code (being documented)

| File | Read it when… |
|---|---|
| `references/code/param-bags.md` | You create or change a param bag, or an API repository method: request vs nested param bags, folder (subfolder of a request), class names, order of the class contents, properties, the API key declared for every property sent and its custom cast, cross-cutting traits and their contracts, validation rules (API keys, kept correct), the include rule and its scope |
| `references/code/api-repositories.md` | You create or change an API repository or one of its methods: folder, class and method names, method order, parameters (optional param bag), body, docblock and `@throws` |
| `references/code/client-repositories.md` | You create or change a client repository: class hierarchy, folder, name, constructor, overridden hooks |
| `references/code/dtos.md` | You create or change a DTO: folder, class name, order of the class contents (an overridden `toArray()` last), properties, data structure, validation rules, exception, includes (commented ones included), nested lists (`init<Property>()`), list DTOs, `toArray()` |
| `references/code/traits.md` | You create or change a trait, or its contract: folder, name, when it has a contract, order of the contents, setters and getters, what it gives to the class using it |
| `references/code/exceptions.md` | You create, throw or catch an exception: `OrigamiApiSdkException`, families and folders, API exceptions and how an API error picks one, names, contents, messages, native exceptions |
| `references/code/enums.md` | You create, rename or use an enum: contents and case names, folder (mirror of the class using it, or thematic), name (with the exception of a class not in the SDK yet), one enum per class, conversion and validation (`Rule::enum()`) |
| `references/code/helpers.md` | You create, change or use a helper: no business logic, folder per subject, name, static methods (and the exception of the classes adapting Laravel), documentation of what it fixes, tests |

🚧 TODO — the rest of the code conventions (contracts outside traits…) is not written yet (see the last general rule).

## General rules

- Prefer explicit checks (`is_null($x)`, `array_is_list($x)`) over implicit logic explained by a comment.
- Merge arrays with the spread (`[...$baseData, ...$override]`), never with `array_merge()`. A literal array is written inline rather than spread (`[...parent::getJsonRequestParamsList(), 'username']`).
- Interpolate variables without braces (`"sellers/$id"`). Braces only when PHP needs them: method call, nested property, or a name followed by a character of an identifier (`"Bearer {$this->oauthToken->getAccessToken()}"`, `"{$name}Dto"`).
- In a class or a trait, the `public` methods first, then the `protected` and `private` ones. In a test class, `setUp()` / `tearDown()` come first. In a DTO or a trait of DTOs, an overridden `toArray()` comes last (see `references/code/dtos.md`).
- Code style is checked by Pint (`pint.json`, Laravel preset, aligned `=>`).
- When a convention is not covered here, do not invent one: follow the closest pattern in the existing code and tests, and tell the user the point is not documented.

## How to contribute

This skill is versioned with the SDK code. Any change goes through a PR, like code.

- **One rule, one place**: each rule is written in a single file; other files refer to it instead of rephrasing it, so that two copies never contradict each other.
- **Refer to real code**: point to existing tests and classes by name (`OrigamiTaxDataApiRepositoryTest::test_create_case_1`) and keep only short excerpts, rather than copying code that will diverge.
- **Resolve a `🚧 TODO`**: replace the TODO with the final rule, in the same file.
- **Add a file or a domain**: a new kind of code (e.g. enums) goes in `references/code/`, an entirely new domain in its own subfolder of `references/`. Add every new file to its index (`references/tests/README.md` for tests, the table above for code), otherwise it may never be read.
- **Change the scope**: reread and update the frontmatter `description`. It is what decides when Claude reads the skill.
