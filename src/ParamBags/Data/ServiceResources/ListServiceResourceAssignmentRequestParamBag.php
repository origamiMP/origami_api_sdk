<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;

class ListServiceResourceAssignmentRequestParamBag extends RequestParamBag implements HasFiltersContract, HasPaginationContract
{
    use HasFilters, HasPagination;

    /**
     * `date`: the assignments covering this day (Y-m-d).
     */
    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'service_site_id',
            'seller_id',
            'service_resource_id',
            'date',
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
