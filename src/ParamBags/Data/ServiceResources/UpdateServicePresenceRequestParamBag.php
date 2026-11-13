<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * Moves the presence to another resource of the place; the bookings follow it.
 */
class UpdateServicePresenceRequestParamBag extends RequestParamBag
{
    public int $serviceResourceId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceResourceId' => 'service_resource_id',
        ];
    }
}
