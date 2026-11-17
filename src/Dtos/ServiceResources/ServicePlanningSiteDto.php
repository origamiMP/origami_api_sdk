<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePlanningSiteDtoNotConstructableException;

/**
 * The attachment of a planning to a place, as saved.
 */
class ServicePlanningSiteDto extends ApiResponseDto
{
    public int $serviceSiteId;

    public string $resourceType;

    public int $bufferBeforeMinutes;

    public int $bufferAfterMinutes;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'service_site_id'       => 'serviceSiteId',
            'resource_type'         => 'resourceType',
            'buffer_before_minutes' => 'bufferBeforeMinutes',
            'buffer_after_minutes'  => 'bufferAfterMinutes',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServicePlanningSiteDtoNotConstructableException($msg, previous: $previous);
    }
}
