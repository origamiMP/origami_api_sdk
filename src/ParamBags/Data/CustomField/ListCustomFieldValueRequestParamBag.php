<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldValueListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListCustomFieldValueRequestParamBag extends RequestParamBag implements HasIncludesContract, HasSearchContract
{
    use HasIncludes, HasSearch;

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getSearchParamsList(),
            $this->getIncludeParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return array_merge(
            $this->getSearchValidationRules(),
            $this->getIncludeValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return CustomFieldValueListDto::class;
    }
}
