<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoMangopayScaStatusEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCustomFields;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class SellerDto extends ApiResponseDto
{
    use HasAvailableIncludes, HasCustomFields, HasTimestamps;

    protected static array $availableIncludes = [
        // 'shipping_offers',
        // 'product_offers',
        // 'tickets',
        // 'documents',
        // 'default_tax',
        // 'payment_reports',
        // 'bank_accounts',
        // 'invoices',
        // 'subscriptions',
        // 'legal_information',
        // 'psp_wallets',
        // 'required_documents',
        // 'visibilities',
        // 'users',
        // 'user_group_users',
        // 'reviews',
        // 'psp_users',
        // 'notifications_sent',
        // 'invoice_tax',
        // 'psp_ubos',
        // 'subscription_lines',
        // 'warehouses',
        // 'translations',
    ];

    public int $id;

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

    public ?SellerDtoMangopayScaStatusEnum $mangopayScaStatus;

    public SellerDtoTypeEnum $type;

    public SellerDtoStateEnum $state;

    public int $defaultTaxId;

    public ?int $feesProfileId;

    public ?int $sellerFeesProfileId;

    public ?int $customerFeesProfileId;

    public bool $isActive;

    public ?int $invoiceTaxId;

    public ?Carbon $holidayDepartureDate;

    public ?Carbon $holidayReturnDate;

    public ?Carbon $deactivatedUntil;

    public bool $isInHoliday;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                       => 'id',
            'user_group_parent_id'     => 'userGroupParentId',
            'firstname'                => 'firstName',
            'lastname'                 => 'lastName',
            'name'                     => 'name',
            'email'                    => 'email',
            'phone'                    => 'phone',
            'phone_area_code'          => 'phoneAreaCode',
            'address_id'               => 'addressId',
            'address_line1'            => 'addressLine1',
            'address_line2'            => 'addressLine2',
            'address_city'             => 'addressCity',
            'address_postcode'         => 'addressPostCode',
            'address_country'          => 'addressCountry',
            'address_country_iso'      => 'addressCountryIso',
            'address_longitude'        => 'addressLongitude',
            'address_latitude'         => 'addressLatitude',
            'address_region'           => 'addressRegion',
            'address_full'             => 'fullAddress',
            'image_url'                => 'imageUrl',
            'image_cover_url'          => 'imageCoverUrl',
            'rate_total'               => 'rateTotal',
            'rate_count'               => 'rateCount',
            'language_iso'             => 'languageIso',
            'language_id'              => 'languageId',
            'external_uid'             => 'externalUid',
            'email_validated'          => 'emailValidated',
            'phone_validated'          => 'phoneValidated',
            'mangopay_sca_status'      => fn ($mangopayScaStatus) => $this->mangopayScaStatus = SellerDtoMangopayScaStatusEnum::tryFrom($mangopayScaStatus),
            'seller_type'              => fn ($sellerType) => $this->type = SellerDtoTypeEnum::tryFrom($sellerType),
            'state'                    => fn ($state) => $this->state = SellerDtoStateEnum::tryFrom($state),
            'default_tax_id'           => 'defaultTaxId',
            'fees_profile_id'          => 'feesProfileId',
            'seller_fees_profile_id'   => 'sellerFeesProfileId',
            'customer_fees_profile_id' => 'customerFeesProfileId',
            'is_active'                => 'isActive',
            'invoice_tax_id'           => 'invoiceTaxId',
            'holiday_departure_date'   => fn ($holidayDepartureDate) => $this->holidayDepartureDate = $holidayDepartureDate ? Carbon::parse($holidayDepartureDate) : $holidayDepartureDate,
            'holiday_return_date'      => fn ($holidayReturnDate) => $this->holidayReturnDate = $holidayReturnDate ? Carbon::parse($holidayReturnDate) : $holidayReturnDate,
            'deactivated_until'        => fn ($deactivatedUntil) => $this->deactivatedUntil = $deactivatedUntil ? Carbon::parse($deactivatedUntil) : $deactivatedUntil,
            'is_in_holiday'            => 'isInHoliday',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
            $this->getCustomFieldsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $mangopayScaStatuses = collect(SellerDtoMangopayScaStatusEnum::cases())->pluck('value');
        $sellerTypes = collect(SellerDtoTypeEnum::cases())->pluck('value');
        $states = collect(SellerDtoStateEnum::cases())->pluck('value');

        $rules = [
            'id'                       => ['required', 'integer'],
            'user_group_parent_id'     => ['required', 'integer'],
            'firstname'                => ['present', 'nullable', 'string'],
            'lastname'                 => ['present', 'nullable', 'string'],
            'name'                     => ['required', 'string'],
            'email'                    => ['required', 'string', 'email'],
            'phone'                    => ['present', 'nullable', 'string'],
            'phone_area_code'          => ['present', 'nullable', 'string'],
            'address_id'               => ['present', 'nullable', 'integer'],
            'address_line1'            => ['present', 'string'],
            'address_line2'            => ['present', 'string'],
            'address_city'             => ['present', 'string'],
            'address_postcode'         => ['present', 'string'],
            'address_country'          => ['present', 'string'],
            'address_country_iso'      => ['present', 'string'],
            'address_longitude'        => ['present', 'string'],
            'address_latitude'         => ['present', 'string'],
            'address_region'           => ['present', 'string'],
            'address_full'             => ['present', 'string'],
            'image_url'                => ['present', 'nullable', 'string'],
            'image_cover_url'          => ['present', 'nullable', 'string'],
            'rate_total'               => ['required', 'numeric', 'min:0'],
            'rate_count'               => ['required', 'integer', 'min:0'],
            'language_iso'             => ['present', 'nullable', 'string'],
            'language_id'              => ['present', 'nullable', 'integer'],
            'external_uid'             => ['present', 'nullable', 'string'],
            'email_validated'          => ['required', 'boolean'],
            'phone_validated'          => ['required', 'boolean'],
            'mangopay_sca_status'      => ['nullable', Rule::in($mangopayScaStatuses)],
            'seller_type'              => ['required', Rule::in($sellerTypes)],
            'state'                    => ['required', Rule::in($states)],
            'default_tax_id'           => ['required', 'integer'],
            // TODO : Add 'required' when feature flag will be merged
            'fees_profile_id'          => ['nullable', 'integer'],
            'seller_fees_profile_id'   => ['nullable', 'integer'],
            'customer_fees_profile_id' => ['nullable', 'integer'],
            'is_active'                => ['required', 'boolean'],
            'invoice_tax_id'           => ['present', 'nullable', 'integer'],
            'holiday_departure_date'   => ['present', 'nullable', 'date'],
            'holiday_return_date'      => ['present', 'nullable', 'date'],
            'deactivated_until'        => ['present', 'nullable', 'date'],
            'is_in_holiday'            => ['required', 'boolean'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
            $this->getCustomFieldsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new SellerDtoNotConstructableException($msg, previous: $previous);
    }
}
