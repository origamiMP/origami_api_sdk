<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * One presence per date of a weekday between two dates, six months at most.
 */
class CreateServicePresenceSeriesRequestParamBag extends RequestParamBag
{
    public int $serviceSiteId;

    public int $serviceResourceId;

    public int $sellerId;

    /** 0 for Sunday to 6 for Saturday. */
    public int $weekday;

    /** H:i, wall-clock time of the place. */
    public string $startTime;

    /** H:i, after the start. */
    public string $endTime;

    /** Y-m-d, the first day of the series. */
    public string $from;

    /** Y-m-d, the last day of the series, six months after its first at most. */
    public string $until;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSiteId'     => 'service_site_id',
            'serviceResourceId' => 'service_resource_id',
            'sellerId'          => 'seller_id',
            'weekday'           => 'weekday',
            'startTime'         => 'start_time',
            'endTime'           => 'end_time',
            'from'              => 'from',
            'until'             => 'until',
        ];
    }
}
