<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceAvailabilityReviewDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceAssignmentDtoNotConstructableException;

/**
 * A resource of a place given to a provider on some days (Y-m-d, both included). One the team gives is approved at
 * once, one the provider asks for is pending until the team answers its review.
 */
class ServiceResourceAssignmentDto extends ApiResponseDto
{
    public int $id;

    public int $serviceSiteId;

    public int $serviceResourceId;

    public string $resourceType;

    public string $resourceName;

    public int $sellerId;

    public string $dateStart;

    public string $dateEnd;

    public ServiceAvailabilityReviewDtoStatusEnum $status;

    public ?int $reviewId;

    public ?string $note;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                  => 'id',
            'service_site_id'     => 'serviceSiteId',
            'service_resource_id' => 'serviceResourceId',
            'resource'            => function (object $resource) {
                $this->resourceType = $resource->type;
                $this->resourceName = $resource->name;
            },
            'seller_id'  => 'sellerId',
            'date_start' => 'dateStart',
            'date_end'   => 'dateEnd',
            'status'     => fn (string $status) => $this->status = ServiceAvailabilityReviewDtoStatusEnum::from($status),
            'review_id'  => 'reviewId',
            'note'       => 'note',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceResourceAssignmentDtoNotConstructableException($msg, previous: $previous);
    }
}
