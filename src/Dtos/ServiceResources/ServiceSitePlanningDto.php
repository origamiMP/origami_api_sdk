<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSitePlanningDtoNotConstructableException;

/**
 * A planning attached to a place: the type of resource each of its bookings takes, and the breaks around them.
 */
class ServiceSitePlanningDto extends ApiResponseDto
{
    public int $id;

    public string $resourceType;

    public int $bufferBeforeMinutes;

    public int $bufferAfterMinutes;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                    => 'id',
            'resource_type'         => 'resourceType',
            'buffer_before_minutes' => 'bufferBeforeMinutes',
            'buffer_after_minutes'  => 'bufferAfterMinutes',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSitePlanningDtoNotConstructableException($msg, previous: $previous);
    }
}
