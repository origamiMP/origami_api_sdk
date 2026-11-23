<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateServiceBookingRequestParamBag extends RequestParamBag
{
    public int $stateId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'stateId' => 'state_id',
        ];
    }
}
