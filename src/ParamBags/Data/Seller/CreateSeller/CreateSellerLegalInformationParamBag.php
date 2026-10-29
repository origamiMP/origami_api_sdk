<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupLegalInformationDtoLegalTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class CreateSellerLegalInformationParamBag extends ParamBag
{
    public UserGroupLegalInformationDtoLegalTypeEnum $legalType;

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
}
