<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateServiceResourceRequestParamBag extends RequestParamBag
{
    public string $type;

    public string $name;

    public bool $isActive;

    public int $position;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'type'     => 'type',
            'name'     => 'name',
            'isActive' => 'is_active',
            'position' => 'position',
        ];
    }
}
