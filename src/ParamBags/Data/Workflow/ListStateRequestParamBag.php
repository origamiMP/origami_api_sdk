<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Workflow;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListStateRequestParamBag extends RequestParamBag implements HasFiltersContract, HasIncludesContract, HasPaginationContract, HasSearchContract
{
    use HasFilters, HasIncludes, HasPagination, HasSearch;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'id',
            'key',
            'workflow_id',
            'workflow_type',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getFiltersParamsList(),
            ...$this->getPaginationParamsList(),
            ...$this->getSearchParamsList(),
            ...$this->getIncludesParamsList(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return StateListDto::class;
    }
}
