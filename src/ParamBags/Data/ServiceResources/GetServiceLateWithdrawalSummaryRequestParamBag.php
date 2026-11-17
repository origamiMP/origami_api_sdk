<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;

class GetServiceLateWithdrawalSummaryRequestParamBag extends RequestParamBag implements HasFiltersContract
{
    use HasFilters;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'seller_id',
            'service_site_id',
            'withdrawn_after',
            'withdrawn_before',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getFiltersParamsList(),
        ];
    }
}
