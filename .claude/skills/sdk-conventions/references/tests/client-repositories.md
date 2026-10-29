# Tests — Client repositories

## Fake HTTP calls (`TestsRestClient`)

No network and no mocking library (`createMock()`, Mockery): a real client repository of the SDK answers with queued Guzzle responses, so all its logic (headers, `v1/` prefix, errors) really runs. API repository tests (see `api-repositories.md`) and client repository tests use the same helpers.

| Helper | Role |
|---|---|
| `mockDataRestClientReturning(...)`, `mockGuestRestClientReturning(...)`, `mockOauthRestClientReturning(...)` | Client repository matching the API repository (`Data`, `Guest`, `Oauth`) |
| `jsonResponse($body, $status = 200)`, `emptyResponse($status = 204)` | JSON response, response without body |
| `new Response($status, [], $rawBody)` | Raw body (HTML, invalid JSON, `{}`…) |
| a `\Throwable` instead of a response | Network failure |
| `useMockedGuzzleClient($restClient, [...])` | Client repository built by hand (custom user agent, user group context) |

## Client repository tests

The behavior shared by every client repository (headers, URL prefix, HTTP verbs, API errors turned into exceptions) is tested in `OrigamiRestClientTest`, through the Guest client repository; what is specific to one client repository is tested in its own file (`OrigamiDataRestClientTest`). These tests call `$restClient->get('some_resource')` directly and read the request with `$this->lastApiRequest()`. API error responses are built with `mockApiErrorsResponse()` and `mockApiErrorData()` (`TestsErrorDtos`).
