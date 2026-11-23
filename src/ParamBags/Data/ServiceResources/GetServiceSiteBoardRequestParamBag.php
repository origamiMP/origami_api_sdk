<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class GetServiceSiteBoardRequestParamBag extends RequestParamBag
{
    /** A day `Y-m-d`, today by default. */
    public string $from;

    /** A day `Y-m-d`, the `from` day by default. */
    public string $to;

    protected function getQueryRequestParamsList(): array
    {
        return [
            'from' => 'from',
            'to'   => 'to',
        ];
    }
}
