<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewPresenceDtoNotConstructableException;

/**
 * The presence a review is about, on its resource, from one wall-clock time of the place to another
 * (`YYYY-MM-DD HH:MM:SS`). The presences of a regular request share their series (`seriesId`).
 */
class ServiceAvailabilityReviewPresenceDto extends ApiResponseDto
{
    public int $id;

    public int $serviceSiteId;

    public int $sellerId;

    public ?string $seriesId;

    public string $startsAt;

    public string $endsAt;

    public int $resourceId;

    public string $resourceType;

    public string $resourceName;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'              => 'id',
            'service_site_id' => 'serviceSiteId',
            'seller_id'       => 'sellerId',
            'series_id'       => 'seriesId',
            'starts_at'       => 'startsAt',
            'ends_at'         => 'endsAt',
            'resource'        => [
                'id'   => 'resourceId',
                'type' => 'resourceType',
                'name' => 'resourceName',
            ],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewPresenceDtoNotConstructableException($msg, previous: $previous);
    }
}
