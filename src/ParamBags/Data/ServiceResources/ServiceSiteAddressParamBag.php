<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class ServiceSiteAddressParamBag extends ParamBag
{
    public string $addressLine1;

    public ?string $addressLine2;

    public string $postcode;

    public string $city;

    public string $country;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'addressLine1' => 'address_line1',
            'addressLine2' => 'address_line2',
            'postcode'     => 'postcode',
            'city'         => 'city',
            'country'      => 'country',
        ];
    }
}
