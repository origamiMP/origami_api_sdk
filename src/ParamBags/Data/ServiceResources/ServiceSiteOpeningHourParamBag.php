<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class ServiceSiteOpeningHourParamBag extends ParamBag
{
    public int $weekday;

    public string $opensAt;

    public string $closesAt;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'weekday'  => 'weekday',
            'opensAt'  => 'opens_at',
            'closesAt' => 'closes_at',
        ];
    }
}
