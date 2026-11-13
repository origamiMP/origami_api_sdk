<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * Moves the provider to another resource of the place over the days of the assignment.
 */
class UpdateServiceResourceAssignmentRequestParamBag extends RequestParamBag
{
    public int $serviceResourceId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceResourceId' => 'service_resource_id',
        ];
    }
}
