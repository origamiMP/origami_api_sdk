<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServicePresenceDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceDtoNotConstructableException;

/**
 * A provider present at a place on a resource (a chair) from one wall-clock time of the place to another: her slots open
 * there only. One the team creates is approved at once, one the provider asks for is pending until the team answers.
 */
class ServicePresenceDto extends ApiResponseDto
{
    public int $id;

    public int $serviceSiteId;

    public int $serviceResourceId;

    public string $resourceType;

    public string $resourceName;

    public int $sellerId;

    /** The series of the regular presences it belongs to, if any. */
    public ?string $seriesId;

    /** Y-m-d H:i:s, wall-clock time of the place. */
    public string $startsAt;

    public string $endsAt;

    public ServicePresenceDtoStatusEnum $status;

    public bool $requestedBySeller;

    public ?int $reviewId;

    public ?string $note;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                  => 'id',
            'service_site_id'     => 'serviceSiteId',
            'service_resource_id' => 'serviceResourceId',
            'resource'            => [
                'type' => 'resourceType',
                'name' => 'resourceName',
            ],
            'seller_id'           => 'sellerId',
            'series_id'           => 'seriesId',
            'starts_at'           => 'startsAt',
            'ends_at'             => 'endsAt',
            'status'              => fn (string $status) => $this->status = ServicePresenceDtoStatusEnum::from($status),
            'requested_by_seller' => 'requestedBySeller',
            'review_id'           => 'reviewId',
            'note'                => 'note',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServicePresenceDtoNotConstructableException($msg, previous: $previous);
    }
}
