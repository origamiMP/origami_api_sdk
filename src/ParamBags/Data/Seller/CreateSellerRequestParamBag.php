<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerParamBagStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerParamBagTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerAddressParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerLegalInformationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class CreateSellerRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public int $userGroupParentId;

    public int $customerId;

    public string $name;

    public string $firstname;

    public string $lastname;

    public string $email;

    public string $phone;

    public string $externalUid;

    public string $phoneAreaCode;

    public string $languageIso;

    public CreateSellerParamBagTypeEnum $sellerType;

    public CreateSellerParamBagStateEnum $state = CreateSellerParamBagStateEnum::VALIDATED;

    public int $defaultTaxId;

    public bool $isActive;

    public int $invoiceTaxId;

    public Carbon $holidayDepartureDate;

    public Carbon $holidayReturnDate;

    public int $feesProfileId;

    public int $sellerFeesProfileId;

    public int $customerFeesProfileId;

    // TODO Onboarding seller : Test with object or array
    public object $additionalInformation;

    /** @var array<int, CreateSellerTranslationParamBag> */
    public array $translations;

    public CreateSellerAddressParamBag $address;

    public CreateSellerLegalInformationParamBag $legalInformation;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'userGroupParentId'     => 'user_group_parent_id',
            'customerId'            => 'customer_id',
            'name'                  => 'name',
            'firstname'             => 'firstname',
            'lastname'              => 'lastname',
            'email'                 => 'email',
            'phone'                 => 'phone',
            'externalUid'           => 'external_uid',
            'phoneAreaCode'         => 'phone_area_code',
            'languageIso'           => 'language_iso',
            'sellerType'            => 'seller_type',
            'state'                 => 'state',
            'defaultTaxId'          => 'default_tax_id',
            'isActive'              => 'is_active',
            'invoiceTaxId'          => 'invoice_tax_id',
            'holidayDepartureDate'  => fn (Carbon $date) => ['holiday_departure_date' => $date->format('Y-m-d')],
            'holidayReturnDate'     => fn (Carbon $date) => ['holiday_return_date' => $date->format('Y-m-d')],
            'feesProfileId'         => 'fees_profile_id',
            'sellerFeesProfileId'   => 'seller_fees_profile_id',
            'customerFeesProfileId' => 'customer_fees_profile_id',
            'additionalInformation' => 'additional_information',
            'translations'          => 'translations',
            'address'               => 'address',
            'legalInformation'      => 'legal_information',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

    protected function validationRulesForProperties(): array
    {
        $hasCustomerId = isset($this->customerId);

        $rules = [
            'user_group_parent_id'     => ['integer', 'missing_with:customer_id'],
            'customer_id'              => ['integer'],
            'name'                     => $this->removeRequiredRuleIf(['string', 'required_without:firstname,lastname', 'max:255'], $hasCustomerId),
            'firstname'                => $this->removeRequiredRuleIf(['string', 'required_without:name', 'max:125'], $hasCustomerId),
            'lastname'                 => $this->removeRequiredRuleIf(['string', 'required_without:name', 'max:125'], $hasCustomerId),
            'email'                    => $this->removeRequiredRuleIf(['string', 'required', 'email', 'max:255'], $hasCustomerId),
            'phone'                    => ['string', 'max:30'],
            'external_uid'             => ['string', 'max:255'],
            'phone_area_code'          => ['string', 'max:255'],
            'language_iso'             => ['string', 'max:2'],
            'seller_type'              => ['string', Rule::in([CreateSellerParamBagTypeEnum::MARKETPLACE, CreateSellerParamBagTypeEnum::DROPSHIPPING])],
            'state'                    => ['string', Rule::in([CreateSellerParamBagStateEnum::VALIDATED])],
            'default_tax_id'           => ['integer'],
            'is_active'                => ['boolean'],
            'invoice_tax_id'           => ['integer'],
            'holiday_departure_date'   => ['date_format:Y-m-d'],
            'holiday_return_date'      => ['date_format:Y-m-d'],
            'fees_profile_id'          => ['integer'],
            'seller_fees_profile_id'   => ['integer'],
            'customer_fees_profile_id' => ['integer'],
        ];

        return [
            ...$rules,
            ...$this->getIncludeValidationRules(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return SellerDto::class;
    }
}
