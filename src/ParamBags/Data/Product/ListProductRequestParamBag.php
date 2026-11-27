<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Product;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;

/**
 * The `id` filter takes a list of ids (`[3, 4]`): the API reads a string separated by commas as a single id.
 */
class ListProductRequestParamBag extends RequestParamBag implements HasFiltersContract, HasIncludesContract, HasPaginationContract
{
    use HasFilters, HasIncludes, HasPagination;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'id',
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
        return ProductListDto::class;
    }
}
