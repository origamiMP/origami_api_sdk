---
name: sdk-conventions
description: "Use when working in origami_api_sdk: creating, changing or reviewing any of its code (API or client repository, param bag, DTO, trait, contract, exception, enum, helper), or writing, fixing or reviewing PHPUnit tests (« ajoute les TUs », \"add tests\", \"cover this code\")."
---

# Conventions — Origami API SDK

## Project context

- PHP SDK (`origami-mp/origami-api-sdk`) calling the central Origami API. It is used by origami_admin_v2, among others.
- Code organization: **API repositories** (an HTTP call in, a DTO out) split between `Data` (authenticated), `Guest` (public) and `Oauth`, **param bags** (the parameters sent), **DTOs** (reading and validating the API response), **client repositories** (sending the HTTP requests of the API repositories), **traits** carrying shared behavior and their **contracts**, **exceptions**, **enums** and generic **helpers**.
- The API code (to check the actual format of its responses) lives in the sibling repo `origami_api`.

## How to use this skill

The skill is split by domain, each in its own subfolder of `references/`.

### Tests

Start with `references/tests/README.md`: it lists every test topic and the single file where its rule is written.

### SDK code

| File | Read it when… |
|---|---|
| `references/code/param-bags.md` | You create or change a param bag, or an API repository method: request vs nested param bags, folder (subfolder of a request), class names, order of the class contents, properties, the API key declared for every property sent and its custom cast, cross-cutting traits and their contracts, no validation rules, the include rule and its scope |
| `references/code/api-repositories.md` | You create or change an API repository or one of its methods: folder, class and method names, method order, parameters (optional param bag), body, docblock and `@throws` |
| `references/code/client-repositories.md` | You create or change a client repository: class hierarchy, folder, name, constructor, overridden hooks |
| `references/code/dtos.md` | You create or change a DTO: folder, class name, order of the class contents (an overridden `toArray()` last), properties, data structure, missing and optional keys (`getOptionalKeys()`), exception, includes (commented ones included), nested lists (`init<Property>()`), list DTOs, `toArray()` |
| `references/code/traits.md` | You create or change a trait, or its contract: folder, name, when it has a contract, order of the contents, setters and getters, what it gives to the class using it |
| `references/code/exceptions.md` | You create, throw or catch an exception: `OrigamiApiSdkException`, families and folders, API exceptions and how an API error picks one, names, contents, messages, native exceptions |
| `references/code/enums.md` | You create, rename or use an enum: contents and case names, folder (mirror of the class using it, or thematic), name (with the exception of a class not in the SDK yet), a param bag reusing the enum of its DTO, changing an enum used in several places, conversion |
| `references/code/helpers.md` | You create, change or use a helper: no business logic, folder per subject, name, static methods, documentation of what it fixes, tests |
| `references/code/contracts.md` | You create, implement or import a contract (interface): when one exists (trait, DTO, other needs), folder, name, contents, `<Name>Contract` alias |

## General rules

- Prefer explicit checks (`is_null($x)`, `array_is_list($x)`) over implicit logic explained by a comment.
- Merge arrays with the spread (`[...$baseData, ...$override]`), never with `array_merge()` nor the `+` operator. A literal array is written inline rather than spread (`[...parent::getJsonRequestParamsList(), 'username' => 'username']`).
- Code style is checked and fixed by Pint (`pint.json`, Laravel preset), the interpolation (braces around every variable, `"sellers/{$id}"`) and the order of the class elements included: traits, cases, constants, properties, constructor, PHPUnit methods (`setUp()`…), then the `public`, `protected` and `private` methods, and `toArray()` last. The reference files only give the order between methods of a same visibility.
- When a convention is not covered here, do not invent one: follow the closest pattern in the existing code and tests, and tell the user the point is not documented.
- The skill covers every way of coding of the SDK as of today. A change introducing a new one (a new kind of file, a base class, a hook, a trait, a way of building or validating data…) documents it in the skill, in the same PR, in the file of its kind of code (see « How to contribute »). When writing or reviewing such a change, strongly suggest the user documents it, with the proposed rule.

## How to contribute

This skill is versioned with the SDK code. Any change goes through a PR, like code.

- **One rule, one place**: each rule is written in a single file; other files refer to it instead of rephrasing it, so that two copies never contradict each other.
- **Refer to real code**: point to existing tests and classes by name (`OrigamiTaxDataApiRepositoryTest::test_create_case_1`) and keep only short excerpts, rather than copying code that will diverge.
- **Resolve a `🚧 TODO`**: replace the TODO with the final rule, in the same file.
- **Add a file or a domain**: a new kind of code (e.g. a new folder of `src/`) goes in `references/code/`, an entirely new domain in its own subfolder of `references/`. Add every new file to its index (`references/tests/README.md` for tests, the table above for code), otherwise it may never be read.
- **Change the scope**: reread and update the frontmatter `description`. It is what decides when Claude reads the skill.
