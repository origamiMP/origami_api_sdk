<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerLegalInformationLegalTypeParamEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class CreateSellerLegalInformationParamBag extends ParamBag
{
    public CreateSellerLegalInformationLegalTypeParamEnum $legalType;

    public string $managerFirstName;

    public string $managerLastName;

    public Carbon $managerBirthday;

    public string $managerCountryIso;

    public string $managerNationalityIso;

    public string $companyName;

    public string $companyContactEmail;

    public string $siret;

    public string $tvaNumber;

    public string $registrationCity;

    public string $legalForm;

    public CreateSellerLegalInformationAddressParamBag $address;

    protected function validationRulesForProperties(): array
    {
        $requiredIfCompanyLegalTypeRules = Rule::requiredIf(in_array($this->legalType, [
            CreateSellerLegalInformationLegalTypeParamEnum::COMPANY,
            CreateSellerLegalInformationLegalTypeParamEnum::SOLE_TRADER,
        ]));

        return [
            'legalType'             => ['required'],
            'managerFirstName'      => ['required', 'string', 'max:255'],
            'managerLastName'       => ['required', 'string', 'max:255'],
            'managerBirthday'       => ['required'],
            'managerCountryIso'     => ['required', 'string', 'max:2'],
            'managerNationalityIso' => ['required', 'string', 'max:2'],
            'companyName'           => [$requiredIfCompanyLegalTypeRules, 'string', 'max:255'],
            'companyContactEmail'   => [$requiredIfCompanyLegalTypeRules, 'email', 'max:255'],
            'siret'                 => [$requiredIfCompanyLegalTypeRules, 'string', 'max:255'],
            'tvaNumber'             => ['string', 'max:255'],
            'registrationCity'      => ['string', 'max:255'],
            'legalForm'             => ['string', 'max:255'],
        ];
    }

    protected function castManagerBirthdayToEncodableType(Carbon $datetime): string|int
    {
        return $datetime->format('Y-m-d');
    }
}
