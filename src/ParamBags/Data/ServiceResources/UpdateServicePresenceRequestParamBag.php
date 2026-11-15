<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * Moves the presence to another resource of the place (`serviceResourceId`), changes its hours (`date`, `startTime` and
 * `endTime`), or both. The bookings follow it: the API refuses a move or hours an upcoming booking could not follow.
 */
class UpdateServicePresenceRequestParamBag extends RequestParamBag
{
    public int $serviceResourceId;

    /** Y-m-d, a day of the place. */
    public string $date;

    /** H:i, wall-clock time of the place. */
    public string $startTime;

    /** H:i, after the start. */
    public string $endTime;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceResourceId' => 'service_resource_id',
            'date'              => 'date',
            'startTime'         => 'start_time',
            'endTime'           => 'end_time',
        ];
    }
}
