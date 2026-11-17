<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesDtoNotConstructableException;

/**
 * The reviews of a series granted, and those left pending because their presence could not be granted.
 */
class ServiceAvailabilityReviewApproveSeriesDto extends ApiResponseDto
{
    /** @var Collection<int, ServiceAvailabilityReviewDto> */
    public Collection $data;

    /** @var Collection<int, ServiceAvailabilityReviewApproveSeriesFailedDto> */
    public Collection $failed;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($reviews) => $this->initData($reviews),
            'meta' => [
                'failed' => fn ($failed) => $this->initFailed($failed),
            ],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewApproveSeriesDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $reviews): void
    {
        $this->data = collect($reviews)->map(fn ($review) => new ServiceAvailabilityReviewDto($review));
    }

    protected function initFailed(array $failed): void
    {
        $this->failed = collect($failed)->map(fn ($failedReview) => new ServiceAvailabilityReviewApproveSeriesFailedDto($failedReview));
    }
}
