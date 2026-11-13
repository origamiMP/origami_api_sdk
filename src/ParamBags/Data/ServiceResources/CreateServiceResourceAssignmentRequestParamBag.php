<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CreateServiceResourceAssignmentRequestParamBag extends RequestParamBag
{
    public int $serviceSiteId;

    public int $serviceResourceId;

    public int $sellerId;

    /** Y-m-d, a day of the place. */
    public string $dateStart;

    /** Y-m-d, included. */
    public string $dateEnd;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSiteId'     => 'service_site_id',
            'serviceResourceId' => 'service_resource_id',
            'sellerId'          => 'seller_id',
            'dateStart'         => 'date_start',
            'dateEnd'           => 'date_end',
        ];
    }
}
