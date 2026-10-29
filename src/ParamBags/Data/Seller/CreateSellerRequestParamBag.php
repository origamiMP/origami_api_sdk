<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerParamBagStateEnum;
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

    public SellerDtoTypeEnum $sellerType;

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
        return $this->getIncludesParamsList();
    }

    protected static function getRequestMainDto(): string
    {
        return SellerDto::class;
    }
}
