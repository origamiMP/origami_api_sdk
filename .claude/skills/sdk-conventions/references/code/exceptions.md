# Code — Exceptions

Every exception of the SDK (`src/Exceptions/`) extends `OrigamiApiSdkException` (abstract), so that a user of the SDK can catch them all at once. Guarded by `OrigamiApiSdkExceptionTest::test_parent_case_1`.

## Families

The family of an exception, and so its folder in `src/Exceptions/`, depends on where it is thrown: an exception thrown by a DTO goes in `Dtos/<Domain>/`, mirroring `src/Dtos/`, and extends `ApiResponseDtoNotConstructableException` (abstract). Two families are particular:

| Family | Folder | Thrown when… | Base |
|---|---|---|---|
| Client | `Client/` | the request got no response (network failure, timeout) | `HttpClientException` |
| API | `Api/` | the API answers an error, or a response that cannot be read | `OrigamiApiException` (abstract) |

### API family

- `OrigamiApiSingleException` (abstract): one error of the API, carrying its `OrigamiApiErrorDto` (`getErrorDto()`, `getOrigamiApiErrorCode()`).
  - `OrigamiApiClientErrorException`: any 4xx, with one subclass per known status (`OrigamiApiBadRequestException`, `OrigamiApiNotFoundException`, `OrigamiApiUnprocessableEntityException`…).
- `OrigamiApiMultipleException`: several errors at once (`getErrorDtos()`).
- `OrigamiApiResponseWithoutDataException`: a response without the `data` key holding the item (`RestApiRepository::getResponseContentData()`).
- `OrigamiApiUnknownException`: a status outside the 4xx (5xx…), an error response that is not an Origami API error (a proxy's HTML page), or a response that cannot be read; `getErrorDto()` is `null` when there is no API error to read.

`OrigamiApiErrorDto::getCorrespondingException()` picks the exception, by error code first (`getCorrespondingExceptionToErrorCode()`), then, for an error code not registered, by HTTP status (`getCorrespondingExceptionToHttpStatusCode()`). A new status gets its subclass of `OrigamiApiClientErrorException` and its line in the second method; a new error code its line in the first one, with its case in `OrigamiApiErrorCodeEnum`.

## Names

- API family: `OrigamiApi<Status>Exception` (`OrigamiApiTooManyRequestsException`).
- DTO family: `<Dto>NotConstructableException` (see `dtos.md`).

## Contents

- A DTO exception adds nothing to its base: `class TaxDtoNotConstructableException extends ApiResponseDtoNotConstructableException {}`.
- An exception carrying data receives it in its constructor, as a `protected` promoted property, exposed by a getter (`OrigamiApiSingleException::getErrorDto()`).
- An exception built from several sources offers one named constructor per source, `createFrom<Source>()`, each building its message (`OrigamiApiUnknownException::createFromUnreadableResponse()`).
- `$previous` is passed on whenever the exception wraps another one, typed `?\Throwable`, or with the precise type of what it always wraps (`HttpClientException` takes a `GuzzleException`).

## Messages

In English, saying what failed and with which value: `"Unreadable response from Origami API: HTTP 502, the body is not valid JSON"`. A message raised by a DTO or a param bag starts with `[<ClassShortName>]` (`ApiResponseDto`, `ParamBag`), so that the caller knows which class failed.

## Native exceptions

As little as possible: only a `\LogicException` for a programming error in the SDK itself, that no API response can cause (`ParamBag`: a custom cast not returning a single API key / value pair). Any failure linked to a request or a response throws an exception of the SDK.

## Declaring them

The API repositories declare what they can throw with `@throws`: see `api-repositories.md`, « Docblock ».

## Tests

- DTO family: every DTO is listed in the provider of `ApiResponseDtoTest` (see `references/tests/dtos-and-shared-behavior.md`).
- API family: the API errors turned into exceptions are tested in `OrigamiRestClientTest` (see `references/tests/client-repositories.md`).
