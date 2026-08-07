<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListCustomFieldRequestParamBag extends RequestParamBag implements HasFiltersContract, HasPaginationContract, HasSearchContract
{
    use HasFilters, HasPagination, HasSearch;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'model_type',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getFiltersParamsList(),
            $this->getPaginationParamsList(),
            $this->getSearchParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return array_merge(
            $this->getFiltersValidationRules(),
            $this->getPaginationValidationRules(),
            $this->getSearchValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return CustomFieldListDto::class;
    }
}
