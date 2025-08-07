<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class UserGroupDto extends ApiResponseDto
{
    use HasAvailableIncludes, HasTimestamps;

    protected static array $availableIncludes = [
        // 'shipping_offers',
        // 'product_offers',
        // 'tickets',
        // 'documents',
        // 'payment_reports',
        // 'bank_accounts',
        // 'invoices',
        // 'subscriptions',
        // 'subscription_lines',
        // 'users',
        // 'legal_information',
        // 'psp_wallets',
        // 'psp_users',
        // 'translations',
        // 'user_group_users',
        // 'mandates',
        // 'customer_groups',
    ];

    public int $id;

    public UserGroupDtoTypeEnum $type;

    public int $userGroupParentId;

    public ?string $firstName;

    public ?string $lastName;

    public string $name;

    public string $email;

    public ?string $phone;

    public ?string $phoneAreaCode;

    public ?int $addressId;

    public string $addressLine1;

    public string $addressLine2;

    public string $addressCity;

    public string $addressPostCode;

    public string $addressCountry;

    public string $addressCountryIso;

    public string $addressLongitude;

    public string $addressLatitude;

    public string $addressRegion;

    public string $fullAddress;

    public ?string $imageUrl;

    public ?string $imageCoverUrl;

    public float $rateTotal;

    public int $rateCount;

    public ?string $languageIso;

    public ?int $languageId;

    public ?string $externalUid;

    public bool $emailValidated;

    public bool $phoneValidated;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                   => 'id',
            'type'                 => fn ($type) => $this->type = UserGroupDtoTypeEnum::from($type),
            'user_group_parent_id' => 'userGroupParentId',
            'firstname'            => 'firstName',
            'lastname'             => 'lastName',
            'name'                 => 'name',
            'email'                => 'email',
            'phone'                => 'phone',
            'phone_area_code'      => 'phoneAreaCode',
            'address_id'           => 'addressId',
            'address_line1'        => 'addressLine1',
            'address_line2'        => 'addressLine2',
            'address_city'         => 'addressCity',
            'address_postcode'     => 'addressPostCode',
            'address_country'      => 'addressCountry',
            'address_country_iso'  => 'addressCountryIso',
            'address_longitude'    => 'addressLongitude',
            'address_latitude'     => 'addressLatitude',
            'address_region'       => 'addressRegion',
            'address_full'         => 'fullAddress',
            'image_url'            => 'imageUrl',
            'image_cover_url'      => 'imageCoverUrl',
            'rate_total'           => 'rateTotal',
            'rate_count'           => 'rateCount',
            'language_iso'         => 'languageIso',
            'language_id'          => 'languageId',
            'external_uid'         => 'externalUid',
            'email_validated'      => 'emailValidated',
            'phone_validated'      => 'phoneValidated',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $types = collect(UserGroupDtoTypeEnum::cases())->pluck('value');

        $rules = [
            'id'                   => ['required', 'integer'],
            'type'                 => ['required', Rule::in($types)],
            'user_group_parent_id' => ['required', 'integer'],
            'firstname'            => ['present', 'nullable', 'string'],
            'lastname'             => ['present', 'nullable', 'string'],
            'name'                 => ['required', 'string'],
            'email'                => ['required', 'string', 'email'],
            'phone'                => ['present', 'nullable', 'string'],
            'phone_area_code'      => ['present', 'nullable', 'string'],
            'address_id'           => ['present', 'nullable', 'integer'],
            'address_line1'        => ['present', 'string'],
            'address_line2'        => ['present', 'string'],
            'address_city'         => ['present', 'string'],
            'address_postcode'     => ['present', 'string'],
            'address_country'      => ['present', 'string'],
            'address_country_iso'  => ['present', 'string'],
            'address_longitude'    => ['present', 'string'],
            'address_latitude'     => ['present', 'string'],
            'address_region'       => ['present', 'string'],
            'address_full'         => ['present', 'string'],
            'image_url'            => ['present', 'nullable', 'string'],
            'image_cover_url'      => ['present', 'nullable', 'string'],
            'rate_total'           => ['required', 'numeric', 'min:0'],
            'rate_count'           => ['required', 'integer', 'min:0'],
            'language_iso'         => ['present', 'nullable', 'string'],
            'language_id'          => ['present', 'nullable', 'integer'],
            'external_uid'         => ['present', 'nullable', 'string'],
            'email_validated'      => ['required', 'boolean'],
            'phone_validated'      => ['required', 'boolean'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupDtoNotConstructableException($msg, previous: $previous);
    }
}
