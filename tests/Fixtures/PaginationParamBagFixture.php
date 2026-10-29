<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;

/**
 * Minimal param bag with pagination, to test the HasPagination trait of param bags: it sends only its pagination,
 * as query.
 */
class PaginationParamBagFixture extends RequestParamBag implements HasPaginationContract
{
    use HasPagination;

    protected function getQueryRequestParamsList(): array
    {
        return $this->getPaginationParamsList();
    }
}
