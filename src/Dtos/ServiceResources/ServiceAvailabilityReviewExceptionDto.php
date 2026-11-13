<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewExceptionDtoNotConstructableException;

/**
 * The opening of availability a review is about. Its times are wall-clock times `H:i:s`.
 */
class ServiceAvailabilityReviewExceptionDto extends ApiResponseDto
{
    public int $id;

    public Carbon $dateStart;

    public ?Carbon $dateEnd;

    public ?string $startTime;

    public ?string $endTime;

    public ?int $capacity;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'         => 'id',
            'date_start' => fn (string $dateStart) => $this->dateStart = Carbon::parse($dateStart),
            'date_end'   => fn (?string $dateEnd) => $this->dateEnd = is_null($dateEnd) ? null : Carbon::parse($dateEnd),
            'start_time' => 'startTime',
            'end_time'   => 'endTime',
            'capacity'   => 'capacity',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewExceptionDtoNotConstructableException($msg, previous: $previous);
    }
}
