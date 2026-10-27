<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerLegalInformationParamBagLegalTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class CreateSellerLegalInformationParamBag extends ParamBag
{
    public CreateSellerLegalInformationParamBagLegalTypeEnum $legalType;

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

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'legalType'             => 'legal_type',
            'managerFirstName'      => 'manager_first_name',
            'managerLastName'       => 'manager_last_name',
            'managerBirthday'       => fn (Carbon $date) => ['manager_birthday' => $date->format('Y-m-d')],
            'managerCountryIso'     => 'manager_country_iso',
            'managerNationalityIso' => 'manager_nationality_iso',
            'companyName'           => 'company_name',
            'companyContactEmail'   => 'company_contact_email',
            'siret'                 => 'siret',
            'tvaNumber'             => 'tva_number',
            'registrationCity'      => 'registration_city',
            'legalForm'             => 'legal_form',
            'address'               => 'address',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $requiredIfCompanyLegalTypeRules = Rule::requiredIf(in_array($this->legalType, [
            CreateSellerLegalInformationParamBagLegalTypeEnum::COMPANY,
            CreateSellerLegalInformationParamBagLegalTypeEnum::SOLE_TRADER,
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
}
