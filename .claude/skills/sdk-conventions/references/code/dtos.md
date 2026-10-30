# Code — DTOs

A DTO reads an API response, and exposes its values as typed properties. It extends `ApiResponseDto`, whose constructor fills the properties from the response. The API is the source of truth: a DTO does not validate the response, the types of its properties and the missing keys are enough to reject one that does not match.

## File organization

`src/Dtos/<Domain>/`, with a subdomain folder when a domain grows (`User/UserGroupInvitation/`). Its exception goes in `src/Exceptions/Dtos/`, at the same path.

## Class names

| DTO of… | Name | Example |
|---|---|---|
| An item | `<Resource>Dto` | `SellerDto` |
| A list (see « List DTOs ») | `<Resource>ListDto` | `SellerListDto` |
| A translation of a resource | `<Resource>TranslationDto` | `TaxTranslationDto` |
| The response of an action | `<Resource><Action>Dto` | `UserGroupInvitationCancelDto` |

Its exception is named `<Dto>NotConstructableException` (`SellerDtoNotConstructableException`).

## Class contents

In this order:

1. the traits (`HasIncludes`, `HasPagination`, `HasTimestamps`…);
2. the properties;
3. `getAvailableIncludes()`, when the DTO uses `HasIncludes`;
4. the other public methods specific to the DTO (`FeatureFlagListDto::isFlagActive()`);
5. `getDefaultDataStructureToProperties()`, `getOptionalKeys()` when the DTO has optional keys, `getDefaultNotConstructableException()`;
6. the other protected methods: first the helpers building a property (`init<Property>()`, see « Nested lists »), then any other helper (`OrigamiApiErrorDto::getCorrespondingExceptionToErrorCode()`);
7. `toArray()`, when the DTO overrides it (see « `toArray()` »): last, below the protected methods, which matter more to understand the DTO.

Overriding the constructor stays exceptional: `FeatureFlagListDto` wraps a bare map into a `flags` key before filling its properties.

## Properties

- `public` and typed. A value the API can send as `null` gives a nullable type (`?int $moduleId`).
- Named in camelCase after the API key (`id_scs_tax` → `$idScsTax`), unless another name makes more sense.
- An enum value is a backed enum (`TaxDtoTypeEnum`), a date a `Carbon` (`HasTimestamps`), a list of DTOs a `Collection` documented by `/** @var Collection<int, TaxTranslationDto> */`.
- An include property stays uninitialized when the include was not requested: its docblock says « May be undefined if the corresponding data was not included. » (`UserDto::$userGroups`).

## Data structure (`getDefaultDataStructureToProperties()`)

Maps each API key to its property: the property name when the value is used as is, otherwise a closure that converts it.

```php
'id'           => 'id',
'type'         => fn ($type) => $this->type = TaxDtoTypeEnum::from($type),
'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, TaxTranslationDto::class),
```

A nested key is written as a nested array or a dotted key (`'file.max_size_kb' => 'fileMaxSizeKb'`, `FormTypesDto`).

## Missing keys (`getOptionalKeys()`)

Every key of the data structure is required: a key missing from the response makes the DTO not constructable (`[SellerDto] Missing key 'email' in the API response`). A key present but `null` is not missing: its property is nullable.

The exceptions:

- the includes (the keys of `getAvailableIncludes()`), always optional;
- the keys the API does not always send, listed in `getOptionalKeys()`, in dotted notation (`FormDto`: `fields_count`, sent only with `with_count`; `OrigamiApiErrorDto`: `data.field`). An optional key missing leaves its property uninitialized.

A key is optional only when the API itself omits it: check its transformer in `origami_api` (a condition, a module hook, a feature flag), never assume it.

A value of the wrong type, an unknown enum value or an unreadable date makes the assignment fail, which makes the DTO not constructable as well. PHP converts compatible scalars on the way (`"42"` to an `int` property gives `42`).

## NotConstructable exception (`getDefaultNotConstructableException()`)

Returns the exception of the DTO, which extends `ApiResponseDtoNotConstructableException` without adding anything:

```php
return new TaxDtoNotConstructableException($msg, previous: $previous);
```

Every new DTO gets a line in the provider of `ApiResponseDtoTest`: see `references/tests/dtos-and-shared-behavior.md`.

## Declaring includes

A DTO whose resource has includes on the API side uses the `HasIncludes` trait (`Traits\Dtos`). Why every include must be declared is in `param-bags.md`.

`getAvailableIncludes()` lists **all** the includes the API offers for the resource, default ones and those restricted to some roles or behind a feature flag included:

- an include whose DTO exists in the SDK is mapped to it, and read in `getDefaultDataStructureToProperties()` with `collectIncluded()` or `makeIncluded()`;
- an include whose DTO does not exist yet stays **as a comment**, to keep the link with the API.

```php
'user_groups' => UserGroupDto::class,
// 'user_group_users',
```

Adding an include later means creating its DTO, uncommenting the line and reading it. Full example: `SellerDto`.

## Nested lists

A list of items that is not an include (no Fractal `data` wrapper) is built by a `protected` helper `init<Property>(array $<property>): void`, which fills the property. Its parameter is typed with exactly what the API sends: `array` for a list, a union when it varies (`HasCustomFields::initAdditionalInformation(object|array …)`: a map of custom fields, or `[]` when it is empty). Do not fall back on `mixed` for convenience: look for what the API actually returns (its transformer in `origami_api`, see `references/tests/mock-data.md`), and use `mixed` only when any value is really possible. The closure parameter is the singular of the property:

```php
'translations' => fn ($translations) => $this->initTranslations($translations),

protected function initTranslations(array $translations): void
{
    $this->translations = collect($translations)->map(fn ($translation) => new CustomFieldGroupTranslationDto($translation));
}
```

## List DTOs

**Any DTO whose response is a collection of items is a list**, whatever its name and whether it is paginated or not: `SellerListDto`, `ModuleListDto`, but also `UserGroupInvitationHistoryDto`. Look at the API response (a Fractal collection) and at the DTO property holding the items, not at the name.

- A paginated list uses `HasPagination`, and returns its structure (`getPaginationAsDataStructureToProperties()`) (`SellerListDto`).
- A non-paginated list holds its items in a `data` property (`ModuleListDto`), next to the other values of the response if any (`UserGroupInvitationSendDto::$message`).
- Both build their items in `initData()`, like any nested list (see above): `fn ($module) => new ModuleDto($module)`.

A list DTO offers the same includes as its item, since the API applies them to each item. It does not copy them, it returns those of its item, so that both never diverge:

```php
public static function getAvailableIncludes(): array
{
    return SellerDto::getAvailableIncludes();
}
```

## `toArray()`

Every DTO is `Arrayable`: `ApiResponseDto::toArray()` exposes the public properties under their own camelCase names, with `arrayFromPublicProperties()`. Users of the SDK get it when they send a DTO as JSON (`response()->json($dto)`, Inertia props): enums give their value, dates `DATE_ATOM`, collections and nested DTOs their own array.

A DTO does not redeclare `Arrayable` nor `toArray()`. It overrides `toArray()` only when its array is not its public properties: `HasPagination` nests the pagination under `meta.pagination`. The override comes last in the class, and in `HasPagination` after the protected methods of the trait.
