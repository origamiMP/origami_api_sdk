# Code — API repositories

An API repository groups the API calls of a resource. Each method sends a request through its client repository (see `client-repositories.md`) and returns the DTO built from the response.

## File organization

`src/Repositories/Api/<Family>/<Domain>/`:

- `<Family>`: `Data` (authenticated API) or `Guest` (public API), the family of the client repository used;
- `<Domain>`: the same folder name as the returned DTOs in `src/Dtos/` (`Seller`, `Form`, `User`…).

`OrigamiOauthApiRepository` is the only API repository of the `Oauth` family, directly in `src/Repositories/Api/Oauth/`.

## Class names

`Origami<Resource><Family>ApiRepository`: `OrigamiSellerDataApiRepository`, `OrigamiTaxGuestApiRepository`. An API repository extends the base class of its family (`OrigamiDataApiRepository`, `OrigamiGuestApiRepository`), whose constructor requires the matching client repository. `OrigamiOauthApiRepository` extends `RestApiRepository` directly.

## Method names

| Request | Method |
|---|---|
| List of the resource (`GET sellers`) | `list` |
| One item (`GET sellers/{id}`) | `get` |
| Creation (`POST sellers`) | `create` |
| Update (`PATCH sellers/{id}`) | `update` |
| Deletion (`DELETE sellers/{id}`) | `delete` |
| Any other request | The action of the endpoint (`activate`, `duplicate`, `checkPending`), or `get<Thing>` for another read (`getLegalTypes`, `getHistory`) |

## Method order

The CRUD methods first, in the order of the table above (`list`, `get`, `create`, `update`, `delete`), then the other methods (`OrigamiFormDataApiRepository`).

## Method contents

### Parameters

The ids of the path first, typed `int`, then the param bag, always named `$paramBag`.

The id of the API repository's resource is named `$id` (`OrigamiCustomFieldDataApiRepository::getValues(int $id, …)`). The id of another resource is named after it (`$sellerId` in `OrigamiSellerDocumentDataApiRepository::getRequiredDocuments()`).

A method receives a param bag as soon as the request has something to send, and always when it offers includes (see `param-bags.md`).

#### Optional param bag

**A param bag is an optional parameter of the method (`?ListSellerRequestParamBag $paramBag = null`) as soon as it only carries optional parameters**: includes, filters, a search, pagination, or properties the API does not require (the `required` rules of the request in `origami_api`).

It is **mandatory** in the signature:
- as soon as it carries at least one value mandatory for the API (a value the API requires, such as the email of `GetUserGroupInvitationHistoryRequestParamBag`);
- when it carries the very content of the request, even when the API requires none of its values: calling `update()` with nothing to update makes no sense (`UpdateFormRequestParamBag`, `UpdateUserRequestParamBag`).

When the method receives `null`, it passes it on to the client repository, which calls the API without parameters (see `OrigamiFormDataApiRepository::get()`).

### Body

1. The call of the client repository: `$response = $this->restClient->get("sellers/{$id}", $paramBag);`.
2. Right below, without a blank line, the decoding:
   - `decodeResponse()` for a JSON object, the usual case;
   - `decodeListResponse()` for a bare JSON list (`getLegalTypes()`).
3. After a blank line, the return of the DTO:
   - for an item, built from the `data` key with `getResponseContentData()`, which throws `OrigamiApiResponseWithoutDataException` when the key is missing (`OrigamiSellerDataApiRepository::get()`);
   - for a list, or a response without `data` key, built from the whole response (`OrigamiSellerDataApiRepository::list()`).

A request answering without a body (204) gives a `void` method, which only calls the client repository (`OrigamiTaxDataApiRepository::update()`).

Any other logic stays exceptional: `OrigamiOauthApiRepository::getOauthToken()` fills the `clientId` and `clientSecret` missing from the param bag with those of its client repository.

### Docblock

- A description only when the name of the method and its signature are not enough: what the response holds (`OrigamiFormDataApiRepository::get()`, the whole tree of the form), how to use it (`update()` of the same class, answering 204), what a parameter is for (`checkPending()`). Never a paraphrase of the name (« Create a new tax »).
- Always the `@throws`, in this order:
  1. `HttpClientException` (network failure);
  2. `OrigamiApiUnknownException` (unreadable response, unknown API error);
  3. `OrigamiApiResponseWithoutDataException`, when the method reads the `data` key;
  4. the « NotConstructable » exception of the returned DTO, when the method returns one;
  5. the specific API exceptions known for the endpoint (`OrigamiApiClientErrorException`, `OrigamiApiMultipleException` on `OrigamiFormDataApiRepository::update()`).

```php
/**
 * @throws HttpClientException
 * @throws OrigamiApiUnknownException
 * @throws OrigamiApiResponseWithoutDataException
 * @throws SellerDtoNotConstructableException
 */
public function get(int $id, ?GetSellerRequestParamBag $paramBag = null): SellerDto
{
    $response = $this->restClient->get("sellers/{$id}", $paramBag);
    $responseContent = $this->decodeResponse($response);

    return new SellerDto($this->getResponseContentData($responseContent));
}
```

## Tests

See `references/tests/api-repositories.md`.
