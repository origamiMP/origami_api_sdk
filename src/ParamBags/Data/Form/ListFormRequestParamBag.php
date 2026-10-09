<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListFormRequestParamBag extends RequestParamBag implements HasFiltersContract, HasPaginationContract, HasSearchContract
{
    use HasFilters, HasPagination, HasSearch;

    /**
     * Sent as `with_count=fields`, which adds `fields_count` to each form.
     */
    public string $withCount;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'is_active',
            'is_purchase_enabled',
            'is_quote_enabled',
            'is_standalone_enabled',
            'context',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getFiltersParamsList(),
            $this->getPaginationParamsList(),
            $this->getSearchParamsList(),
            ['withCount'],
        );
    }

    protected function validationRulesForProperties(): array
    {
        return array_merge(
            $this->getFiltersValidationRules(),
            $this->getPaginationValidationRules(),
            $this->getSearchValidationRules(),
            ['with_count' => ['string', 'in:fields']],
        );
    }
}
