<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateServicePlanningSiteRequestParamBag extends RequestParamBag
{
    public int $serviceSiteId;

    public string $resourceType;

    public int $bufferBeforeMinutes;

    public int $bufferAfterMinutes;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSiteId'       => 'service_site_id',
            'resourceType'        => 'resource_type',
            'bufferBeforeMinutes' => 'buffer_before_minutes',
            'bufferAfterMinutes'  => 'buffer_after_minutes',
        ];
    }
}
