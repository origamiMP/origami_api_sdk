<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewRejectSeriesDtoNotConstructableException;

/**
 * The reviews of a series rejected, all with the same note.
 */
class ServiceAvailabilityReviewRejectSeriesDto extends ApiResponseDto
{
    /** @var Collection<int, ServiceAvailabilityReviewDto> */
    public Collection $data;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($reviews) => $this->initData($reviews),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewRejectSeriesDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $reviews): void
    {
        $this->data = collect($reviews)->map(fn ($review) => new ServiceAvailabilityReviewDto($review));
    }
}
