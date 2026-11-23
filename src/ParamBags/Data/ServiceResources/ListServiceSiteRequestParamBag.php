<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;

class ListServiceSiteRequestParamBag extends RequestParamBag implements HasFiltersContract, HasPaginationContract
{
    use HasFilters, HasPagination;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'is_active',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getFiltersParamsList(),
            ...$this->getPaginationParamsList(),
        ];
    }
}
