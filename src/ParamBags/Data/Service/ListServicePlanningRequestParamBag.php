<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServicePlanningListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;

class ListServicePlanningRequestParamBag extends RequestParamBag implements HasFiltersContract, HasIncludesContract, HasPaginationContract
{
    use HasFilters, HasIncludes, HasPagination;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'product_id',
            'product_offer_id',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getFiltersParamsList(),
            ...$this->getPaginationParamsList(),
            ...$this->getIncludesParamsList(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return ServicePlanningListDto::class;
    }
}
