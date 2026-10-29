# Code — Contracts

A contract is an interface letting the users of the SDK, and the SDK itself, depend on a feature rather than on a concrete class. It can concern any kind of file of the SDK. Today:

| Contract of… | Exists when… | Example |
|---|---|---|
| A trait | the users of the SDK call the public methods of the trait on any class using it (see `traits.md`) | `Contracts/Traits/ParamBags/HasPagination` |
| A DTO | a user of the SDK must be able to give the SDK its own implementation instead of the DTO | `Contracts/Dtos/Oauth/OauthToken`, implemented by `OauthTokenDto` and by the token `origami_admin_v2` rebuilds from its session |

These cases are not exhaustive: another need of the same kind (depending on a feature, or letting the user give its own implementation) can justify a new contract.

## File organization

`src/Contracts/<Kind>/…`, mirroring the file it is the contract of: `Contracts/Dtos/Oauth/` for `Dtos/Oauth/OauthTokenDto`, `Contracts/Traits/ParamBags/` for `Traits/ParamBags/`.

## Names

- Contract of a trait: the name of the trait (`HasPagination`).
- Contract of a DTO: the name of the DTO without `Dto` (`OauthToken`).

A contract of a new kind of file gets its naming rule when it is created, written here.

## Contents

- A one-line docblock saying what the contract represents and who implements it.
- Only the public methods the users of the contract need, typed.

## Usage

- A contract is always imported with the alias `<Name>Contract` (`use …\Contracts\Dtos\Oauth\OauthToken as OauthTokenContract;`), so that it never collides with the class or the trait of the same name.
- Where a user of the SDK can give its own implementation, the SDK only asks for the contract: `OrigamiDataRestClient::__construct(string $apiUri, OauthTokenContract $oauthToken, …)`.
