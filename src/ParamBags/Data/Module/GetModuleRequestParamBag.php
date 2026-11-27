<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class GetModuleRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludesParamsList();
    }

    protected static function getRequestMainDto(): string
    {
        return ModuleDto::class;
    }
}
