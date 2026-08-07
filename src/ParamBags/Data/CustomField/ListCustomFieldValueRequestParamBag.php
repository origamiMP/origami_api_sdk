<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListCustomFieldValueRequestParamBag extends RequestParamBag implements HasSearchContract
{
    use HasSearch;

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getSearchParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return $this->getSearchValidationRules();
    }
}
