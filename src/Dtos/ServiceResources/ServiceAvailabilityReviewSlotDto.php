<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewSlotDtoNotConstructableException;

/**
 * The slot added by hand a review is about.
 */
class ServiceAvailabilityReviewSlotDto extends ApiResponseDto
{
    public int $id;

    public Carbon $startsAt;

    public Carbon $endsAt;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'        => 'id',
            'starts_at' => fn (string $startsAt) => $this->startsAt = Carbon::parse($startsAt),
            'ends_at'   => fn (string $endsAt) => $this->endsAt = Carbon::parse($endsAt),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewSlotDtoNotConstructableException($msg, previous: $previous);
    }
}
