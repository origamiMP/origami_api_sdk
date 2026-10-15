# Tests — Index

Unit tests only (`tests/Unit`, PHPUnit 12, PHP 8.4). Each rule is written in a single file: find it here, then read that file.

| Topic | File |
|---|---|
| Where to put a test file, a fixture or a helper trait; registering a trait in `TestCase` | `structure.md` |
| Making a `protected` method testable in a fixture | `structure.md` |
| Method naming, docblocks, test body, assertions, exceptions, data providers, usual values | `writing-tests.md` |
| Fake HTTP client, what to check on a request, expected coverage of a repository, include tests | `repositories.md` |
| REST client tests | `repositories.md` |
| Mock data (`Tests<Domain>Dtos`), ids, actual API format, missing keys | `mock-data.md` |
| DTO tests, error case of every DTO, guard tests | `dtos-and-shared-behavior.md` |
| Traits and abstract classes: testing through a fixture | `dtos-and-shared-behavior.md` |

To finish, `vendor/bin/phpunit` and `vendor/bin/pint --test` must pass.
