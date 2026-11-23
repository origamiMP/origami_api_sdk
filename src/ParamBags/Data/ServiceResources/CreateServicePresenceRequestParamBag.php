<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CreateServicePresenceRequestParamBag extends RequestParamBag
{
    public int $serviceSiteId;

    public int $serviceResourceId;

    public int $sellerId;

    /** Y-m-d, a day of the place. */
    public string $date;

    /** H:i, wall-clock time of the place. */
    public string $startTime;

    /** H:i, after the start. */
    public string $endTime;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSiteId'     => 'service_site_id',
            'serviceResourceId' => 'service_resource_id',
            'sellerId'          => 'seller_id',
            'date'              => 'date',
            'startTime'         => 'start_time',
            'endTime'           => 'end_time',
        ];
    }
}
