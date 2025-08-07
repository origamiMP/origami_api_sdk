<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerStateParamEnum;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerTypeParamEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerAddressParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerLegalInformationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class CreateSellerRequestParamBag extends RequestParamBag
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

    public CreateSellerTypeParamEnum $sellerType;

    public CreateSellerStateParamEnum $state = CreateSellerStateParamEnum::VALIDATED;

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

    protected function getJsonRequestParamsList(): array
    {
        return [
            'userGroupParentId',
            'customerId',
            'name',
            'firstname',
            'lastname',
            'email',
            'phone',
            'externalUid',
            'phoneAreaCode',
            'languageIso',
            'sellerType',
            'state',
            'defaultTaxId',
            'isActive',
            'invoiceTaxId',
            'holidayDepartureDate',
            'holidayReturnDate',
            'feesProfileId',
            'sellerFeesProfileId',
            'customerFeesProfileId',
            'additionalInformation',
            'translations',
            'address',
            'legalInformation',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $hasCustomerId = isset($this->customerId);

        return [
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
            'sellerType'            => ['string', Rule::in([CreateSellerTypeParamEnum::MARKETPLACE, CreateSellerTypeParamEnum::DROPSHIPPING])],
            'state'                 => ['string', Rule::in([CreateSellerStateParamEnum::VALIDATED])],
            'defaultTaxId'          => ['integer'],
            'isActive'              => ['boolean'],
            'invoiceTaxId'          => ['integer'],
            'holidayDepartureDate'  => [],
            'holidayReturnDate'     => [],
            'feesProfileId'         => ['integer'],
            'sellerFeesProfileId'   => ['integer'],
            'customerFeesProfileId' => ['integer'],
        ];
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
