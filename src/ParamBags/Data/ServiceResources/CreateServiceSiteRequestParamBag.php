<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CreateServiceSiteRequestParamBag extends RequestParamBag
{
    public string $name;

    public string $timezone;

    public ?string $phone;

    public ?string $email;

    public bool $isActive;

    public ServiceSiteAddressParamBag $address;

    /** @var array<int, ServiceSiteOpeningHourParamBag> */
    public array $openingHours;

    /** @var array<int, int> */
    public array $sellerIds;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'name'         => 'name',
            'timezone'     => 'timezone',
            'phone'        => 'phone',
            'email'        => 'email',
            'isActive'     => 'is_active',
            'address'      => 'address',
            'openingHours' => 'opening_hours',
            'sellerIds'    => 'seller_ids',
        ];
    }
}
