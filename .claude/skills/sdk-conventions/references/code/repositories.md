# Code — Repositories

A repository groups the API calls of a resource. Each method sends a request (with its param bag) and returns the DTO built from the response.

🚧 TODO — the rest of the repository conventions (method naming, response decoding, declared exceptions…) is not written yet (see the general rules in `SKILL.md`).

## Optional param bag

**A param bag is an optional parameter of the method (`?ListSellerRequestParamBag $paramBag = null`) as soon as it only carries optional parameters**: includes, filters, a search, pagination, or properties that are not `required` in its validation rules (`validationRulesForProperties()`).

It is **mandatory** in the signature:
- as soon as it carries at least one value mandatory for the API (a `required` property, such as the email of `GetUserGroupInvitationHistoryRequestParamBag`);
- when it carries the very content of the request, even without `required` property: calling `update()` with nothing to update makes no sense (`UpdateFormRequestParamBag`, `UpdateUserRequestParamBag`).

When the method receives `null`, it passes it on to the REST client, which calls the API without parameters (see `OrigamiFormDataApiRepository::get()`).
