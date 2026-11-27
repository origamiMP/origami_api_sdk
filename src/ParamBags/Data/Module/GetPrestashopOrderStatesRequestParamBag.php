<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class GetPrestashopOrderStatesRequestParamBag extends RequestParamBag
{
    public int $shopId;

    protected function getQueryRequestParamsList(): array
    {
        return [
            'shopId' => 'shop-id',
        ];
    }
}
