<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

abstract class AddressParamBag extends ParamBag
{
    public string $addressLine1;

    public string $addressLine2;

    public string $postcode;

    public string $city;

    public string $countryIso;

    public string $longitude;

    public string $latitude;

    public string $region;

    protected function getApiKeysToProperties(): array
    {
        return [
            'address_line1' => 'addressLine1',
            'address_line2' => 'addressLine2',
            'postcode'      => 'postcode',
            'city'          => 'city',
            'country_iso'   => 'countryIso',
            'longitude'     => 'longitude',
            'latitude'      => 'latitude',
            'region'        => 'region',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'addressLine1' => ['required', 'string', 'max:255'],
            'addressLine2' => ['required', 'string', 'max:255'],
            'postcode'     => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:255'],
            'countryIso'   => ['required', 'string', 'max:2'],
            'longitude'    => ['string', 'max:255'],
            'latitude'     => ['string', 'max:255'],
            'region'       => ['string', 'max:255'],
        ];
    }
}
