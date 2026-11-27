<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListServiceBookingRequestParamBag extends RequestParamBag implements HasFiltersContract, HasIncludesContract, HasPaginationContract, HasSearchContract
{
    use HasFilters, HasIncludes, HasPagination, HasSearch;

    /**
     * Sorts the list: `name` is the field (`starts_at`), `way` is `asc` (default) or `desc`.
     *
     * @var array{name: string, way?: string}
     */
    public array $order;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'status',
            'state_id',
            'origin',
            'seller_id',
            'starts_between',
            'service_slot_id',
            'service_planning_id',
            // the filters of the plannings, `filter[planning][service_site_id]` for instance
            'planning',
            'product_id',
            'reference',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getFiltersParamsList(),
            ...$this->getPaginationParamsList(),
            ...$this->getSearchParamsList(),
            ...$this->getIncludesParamsList(),
            'order' => 'order',
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return ServiceBookingListDto::class;
    }
}
