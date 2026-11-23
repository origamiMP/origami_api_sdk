<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesFailedDtoNotConstructableException;

/**
 * A review of a series left pending, with the error code telling why its presence could not be granted.
 */
class ServiceAvailabilityReviewApproveSeriesFailedDto extends ApiResponseDto
{
    public int $serviceAvailabilityReviewId;

    public int $servicePresenceId;

    public string $code;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'service_availability_review_id' => 'serviceAvailabilityReviewId',
            'service_presence_id'            => 'servicePresenceId',
            'code'                           => 'code',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewApproveSeriesFailedDtoNotConstructableException($msg, previous: $previous);
    }
}
