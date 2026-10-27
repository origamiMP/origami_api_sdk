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
        $requiredIfCompanyLegalTypeRules = Rule::requiredIf(isset($this->legalType) && in_array($this->legalType, [
            CreateSellerLegalInformationParamBagLegalTypeEnum::COMPANY,
            CreateSellerLegalInformationParamBagLegalTypeEnum::SOLE_TRADER,
        ]));

        return [
            'legal_type'              => ['required', 'string'],
            'manager_first_name'      => ['required', 'string', 'max:255'],
            'manager_last_name'       => ['required', 'string', 'max:255'],
            'manager_birthday'        => ['required', 'date_format:Y-m-d'],
            'manager_country_iso'     => ['required', 'string', 'max:2'],
            'manager_nationality_iso' => ['required', 'string', 'max:2'],
            'company_name'            => [$requiredIfCompanyLegalTypeRules, 'string', 'max:255'],
            'company_contact_email'   => [$requiredIfCompanyLegalTypeRules, 'email', 'max:255'],
            'siret'                   => [$requiredIfCompanyLegalTypeRules, 'string', 'max:255'],
            'tva_number'              => ['string', 'max:255'],
            'registration_city'       => ['string', 'max:255'],
            'legal_form'              => ['string', 'max:255'],
        ];
    }
}
