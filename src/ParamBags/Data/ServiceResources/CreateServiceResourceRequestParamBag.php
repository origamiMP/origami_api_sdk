<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CreateServiceResourceRequestParamBag extends RequestParamBag
{
    public int $serviceSiteId;

    public string $type;

    public string $name;

    public bool $isActive;

    public int $position;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSiteId' => 'service_site_id',
            'type'          => 'type',
            'name'          => 'name',
            'isActive'      => 'is_active',
            'position'      => 'position',
        ];
    }
}
