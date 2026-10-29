# Code — Client repositories

A client repository sends the HTTP requests of the API repositories (see `api-repositories.md`) and turns the API errors into exceptions. An API repository never uses Guzzle directly.

## Class hierarchy

| Class | Role |
|---|---|
| `RestClientRepository` (abstract) | Generic client repository on top of Guzzle: HTTP verbs, timeouts, `beforeApiCall()` / `afterApiCall()` hooks, resource prefix, headers |
| `OrigamiRestClient` (abstract) | Origami API: base URL, `v1` prefix, JSON and `User-Agent` headers, API errors turned into exceptions (`handleRequestError()`) |
| `OrigamiDataRestClient`, `OrigamiGuestRestClient`, `OrigamiOauthRestClient` | One client repository per family of API repositories (`Data`, `Guest`, `Oauth`) |

A behavior shared by every client repository of the Origami API goes in `OrigamiRestClient`; only the behavior of one family goes in its own client repository.

## File organization

`src/Repositories/Client/<Family>/`, with the abstract classes at the root of `src/Repositories/Client/`.

## Class names

`Origami<Family>RestClient`.

## Contents

- The constructor receives the API URI first, then the credentials of the family, and calls `parent::__construct($apiUri)` (`OrigamiOauthRestClient`).
- Credentials and context are `private` properties, with a public getter when an API repository needs them (`OrigamiOauthRestClient::getApiClientId()`).
- A client repository adapts the requests by overriding the hooks of its parents, never `callRestApi()`:
  - `getAdditionalHeaders()`: completes the headers of the parent, merged with the spread (`OrigamiDataRestClient`, `Authorization` and `context` headers);
  - `getResourcePrefix()`: replaces the `v1` prefix (`OrigamiOauthRestClient`, without prefix).

## Tests

See `references/tests/client-repositories.md`.
