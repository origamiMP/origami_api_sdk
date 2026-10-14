# Code — Repositories

A repository groups the API calls of a resource. Each method sends a request (with its param bag) and returns the DTO built from the response.

🚧 TODO — the rest of the repository conventions (method naming, response decoding, declared exceptions…) is not written yet. In the meantime, follow the closest pattern in `src/Repositories/` and tell the user the point is not documented.

## Optional param bag

A param bag can be an **optional parameter** of the method (`?GetFormRequestParamBag $paramBag = null`) **only if it only sends optional parameters**:

- includes;
- filters;
- a search;
- pagination;
- properties that are not `required` in its validation rules (`validationRulesForProperties()`).

As soon as a param bag carries at least one value mandatory for the API (a `required` property in its validation rules, such as the email of `GetUserGroupInvitationHistoryRequestParamBag`), it is mandatory in the signature.

An optional param bag allows adding includes to an existing method without breaking the applications already calling it without argument (see the include rule in `param-bags.md`). When the method receives `null`, it calls the API without parameters:

```php
public function get(int $id, ?GetFormRequestParamBag $paramBag = null): FormDto
{
    $response = $this->restClient->get("forms/{$id}", $paramBag);
    // ...
}
```
