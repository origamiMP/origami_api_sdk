<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;

class ListServicePresenceRequestParamBag extends RequestParamBag implements HasFiltersContract, HasPaginationContract
{
    use HasFilters, HasPagination;

    /**
     * `date`: the presences overlapping this day (Y-m-d); `between`: those overlapping the days between two dates
     * (`Y-m-d,Y-m-d`).
     */
    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'service_site_id',
            'seller_id',
            'service_resource_id',
            'date',
            'between',
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
