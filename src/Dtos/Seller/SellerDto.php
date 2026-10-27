<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoMangopayScaStatusEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCustomFields;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class SellerDto extends ApiResponseDto implements Arrayable
{
    use HasCustomFields, HasIncludes, HasTimestamps;

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

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var SellerDocumentDto[]|Collection
     */
    public Collection $documents;

    /**
     * May be undefined if the corresponding data was not included, or if the seller has no default tax.
     */
    public TaxDto $defaultTax;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var SellerBankAccountDto[]|Collection
     */
    public Collection $bankAccounts;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var DocumentTypeDto[]|Collection
     */
    public Collection $requiredDocuments;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var UserDto[]|Collection
     */
    public Collection $users;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'shipping_offers',
            // 'product_offers',
            // 'tickets',
            'documents'   => SellerDocumentDto::class,
            'default_tax' => TaxDto::class,
            // 'payment_reports',
            'bank_accounts' => SellerBankAccountDto::class,
            // 'invoices',
            // 'subscriptions',
            // 'legal_information',
            // 'psp_wallets',
            // 'current_psp_wallet',
            'required_documents' => DocumentTypeDto::class,
            // 'visibilities',
            'users' => UserDto::class,
            // 'user_group_users',
            // 'reviews',
            // 'psp_users',
            // 'current_psp_user',
            // 'notifications_sent',
            // 'invoice_tax',
            // 'psp_ubos',
            // 'subscription_lines',
            // 'warehouses',
            // 'translations',
            // 'lifecycle_state',
        ];
    }

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
            'mangopay_sca_status'      => fn ($mangopayScaStatus) => $this->mangopayScaStatus = SellerDtoMangopayScaStatusEnum::tryFrom($mangopayScaStatus ?? ''),
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

            'documents'          => fn (object $documents) => $this->documents = $this->collectIncluded($documents, SellerDocumentDto::class),
            'default_tax'        => fn (object $defaultTax) => $this->defaultTax = $this->makeIncluded($defaultTax, TaxDto::class),
            'bank_accounts'      => fn (object $bankAccounts) => $this->bankAccounts = $this->collectIncluded($bankAccounts, SellerBankAccountDto::class),
            'required_documents' => fn (object $requiredDocuments) => $this->requiredDocuments = $this->collectIncluded($requiredDocuments, DocumentTypeDto::class),
            'users'              => fn (object $users) => $this->users = $this->collectIncluded($users, UserDto::class),
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
            ...$this->getCustomFieldsAsDataStructureToProperties(),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $mangopayScaStatuses = collect(SellerDtoMangopayScaStatusEnum::cases())->pluck('value');
        $sellerTypes = collect(SellerDtoTypeEnum::cases())->pluck('value');
        $states = collect(SellerDtoStateEnum::cases())->pluck('value');

        $rules = [
            'id'                   => ['required', 'integer'],
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
            'mangopay_sca_status'  => ['nullable', Rule::in($mangopayScaStatuses)],
            'seller_type'          => ['required', Rule::in($sellerTypes)],
            'state'                => ['required', Rule::in($states)],
            'default_tax_id'       => ['required', 'integer'],
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

        return [
            ...$rules,
            ...$this->getTimestampsValidationRules(),
            ...$this->getCustomFieldsValidationRules(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new SellerDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
