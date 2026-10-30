# Code — Enums

An enum lists the values the API accepts or returns for a field, or a fixed set of values used by the SDK itself (HTTP methods, error codes).

## Contents

- A native PHP enum, backed by `string`, without methods.
- Cases in SCREAMING_SNAKE_CASE, named after their value (`case NOT_EQUALS = 'not_equals'`, `case REFRESH_TOKEN = 'refresh_token'`). A value that is not a word gets a descriptive name: an error code (`case FORM_IN_USE = '140003'`), an option of a library (`case FORM = 'form_params'`).

## File organization

An enum used by a class mirrors the folder of this class, under `src/Enums/`:

- `Enums/Dtos/<Domain>/` for a DTO (`Enums/Dtos/Tax/TaxDtoTypeEnum` for `Dtos/Tax/TaxDto`);
- `Enums/ParamBags/<Family>/<Domain>/`, with the subfolder of the request when there is one, for a param bag having its own enum (`Enums/ParamBags/Data/Seller/CreateSellerParamBagStateEnum`).

An enum belonging to no class goes in a thematic folder: `Error/` (`OrigamiApiErrorCodeEnum`), `Http/` (`HttpRequestMethodEnum`), `Json/` (`JsonResponseErrorEnum`).

## Names

`<Class><Property>Enum`: the class using it and the property it types (`TaxDtoTypeEnum` for `TaxDto::$type`, `SellerDtoMangopayScaStatusEnum` for `SellerDto::$mangopayScaStatus`). For a request param bag, the class name drops `Request`: `CreateSellerParamBagStateEnum` for `CreateSellerRequestParamBag::$state`, `GetOauthTokenParamBagGrantTypeEnum` for `GetOauthTokenRequestParamBag::$grantType`.

📢 Documented exception: an enum of an API data whose class does not exist in the SDK yet is named after that future class, in its future folder: `Enums/Dtos/User/UserGroupLegalInformationDtoLegalTypeEnum`, for the legal information of a user group, used today by `SellerLegalTypeListDto` and `CreateSellerLegalInformationParamBag`.

## Shared enums

A param bag reuses the enum of the matching DTO (`CreateTaxRequestParamBag::$type` is a `TaxDtoTypeEnum`). It has its own enum only when it accepts part of the values the API returns: `CreateSellerParamBagStateEnum` only has `VALIDATED`, `SellerDtoStateEnum` has 3 cases.

Changing an enum used in several places means checking, for each of them, that the new version still applies. A value added because the API now returns it may not be accepted in a request: the param bag then gets its own enum, without that value.

## Usage

- In a DTO, the value is converted in the data structure (`fn ($type) => $this->type = TaxDtoTypeEnum::from($type)`) (see `dtos.md`): an unknown value makes the DTO not constructable.
- In a param bag, the property is typed with the enum and sent as its value automatically (see `param-bags.md`).
