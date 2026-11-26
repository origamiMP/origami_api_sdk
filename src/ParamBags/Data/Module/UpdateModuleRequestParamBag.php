<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateModuleRequestParamBag extends RequestParamBag
{
    public array $configurations;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'configurations' => 'configurations',
        ];
    }
}
