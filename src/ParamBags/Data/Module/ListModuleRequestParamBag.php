<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class ListModuleRequestParamBag extends RequestParamBag
{
    use HasIncludes;

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getIncludeParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return $this->getIncludeValidationRules();
    }

    protected static function getRequestMainDto(): string
    {
        return ModuleListDto::class;
    }
}
