<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;

/**
 * `starts_after` and `starts_before` bound the starts of the slots (wall-clock times of the place, `Y-m-d H:i:s`).
 */
class ListServiceSlotRequestParamBag extends RequestParamBag implements HasFiltersContract, HasPaginationContract
{
    use HasFilters, HasPagination;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'status',
            'service_planning_id',
            'starts_after',
            'starts_before',
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
