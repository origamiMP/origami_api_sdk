<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;

/**
 * Minimal param bag with filters, to test the HasFilters trait of param bags: it adds an `is_active` filter to the
 * base ones, sends only its filters, as query.
 */
class FiltersParamBagFixture extends RequestParamBag
{
    use HasFilters;

    protected static function getAdditionalAvailableFilters(): array
    {
        return ['is_active'];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getFiltersParamsList();
    }
}
