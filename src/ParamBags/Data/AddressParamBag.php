<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data;

class AddressParamBag
{
    public string $addressLine1;

    public string $addressLine2;

    public string $postcode;

    public string $city;

    public string $countryIso;

    public string $longitude;

    public string $latitude;

    public string $region;

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
