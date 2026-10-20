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

    /**
     * @var CreateSellerTranslationParamBag[]
     */
    public array $translations;

    public CreateSellerAddressParamBag $address;

    public CreateSellerLegalInformationParamBag $legalInformation;

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

    protected function getJsonRequestParamsList(): array
    {
        return [
            'user_group_parent_id'     => 'userGroupParentId',
            'customer_id'              => 'customerId',
            'name'                     => 'name',
            'firstname'                => 'firstname',
            'lastname'                 => 'lastname',
            'email'                    => 'email',
            'phone'                    => 'phone',
            'external_uid'             => 'externalUid',
            'phone_area_code'          => 'phoneAreaCode',
            'language_iso'             => 'languageIso',
            'seller_type'              => 'sellerType',
            'state'                    => 'state',
            'default_tax_id'           => 'defaultTaxId',
            'is_active'                => 'isActive',
            'invoice_tax_id'           => 'invoiceTaxId',
            'holiday_departure_date'   => 'holidayDepartureDate',
            'holiday_return_date'      => 'holidayReturnDate',
            'fees_profile_id'          => 'feesProfileId',
            'seller_fees_profile_id'   => 'sellerFeesProfileId',
            'customer_fees_profile_id' => 'customerFeesProfileId',
            'additional_information'   => 'additionalInformation',
            'translations'             => 'translations',
            'address'                  => 'address',
            'legal_information'        => 'legalInformation',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $hasCustomerId = isset($this->customerId);

        $rules = [
            'userGroupParentId'     => ['integer', 'missing_with:customerId'],
            'customerId'            => ['integer'],
            'name'                  => $this->removeRequiredRuleIf(['string', 'required_without:firstname,lastname', 'max:255'], $hasCustomerId),
            'firstname'             => $this->removeRequiredRuleIf(['string', 'required_without:name', 'max:125'], $hasCustomerId),
            'lastname'              => $this->removeRequiredRuleIf(['string', 'required_without:name', 'max:125'], $hasCustomerId),
            'email'                 => $this->removeRequiredRuleIf(['string', 'required', 'email', 'max:255'], $hasCustomerId),
            'phone'                 => ['string', 'max:30'],
            'externalUid'           => ['string', 'max:255'],
            'phoneAreaCode'         => ['string', 'max:255'],
            'languageIso'           => ['string', 'max:2'],
            'sellerType'            => ['string', Rule::in([CreateSellerParamBagTypeEnum::MARKETPLACE, CreateSellerParamBagTypeEnum::DROPSHIPPING])],
            'state'                 => ['string', Rule::in([CreateSellerParamBagStateEnum::VALIDATED])],
            'defaultTaxId'          => ['integer'],
            'isActive'              => ['boolean'],
            'invoiceTaxId'          => ['integer'],
            'holidayDepartureDate'  => [],
            'holidayReturnDate'     => [],
            'feesProfileId'         => ['integer'],
            'sellerFeesProfileId'   => ['integer'],
            'customerFeesProfileId' => ['integer'],
        ];

        return array_merge(
            $rules,
            $this->getIncludeValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return SellerDto::class;
    }

    protected function castHolidayDepartureDateToEncodableType(Carbon $datetime): string|int
    {
        return $datetime->format('Y-m-d');
    }

    protected function castHolidayReturnDateToEncodableType(Carbon $datetime): string|int
    {
        return $datetime->format('Y-m-d');
    }
}
