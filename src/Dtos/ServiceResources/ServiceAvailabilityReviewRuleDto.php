<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewRuleDtoNotConstructableException;

/**
 * The weekly rule of availability a review is about. Its times are wall-clock times `H:i:s`.
 */
class ServiceAvailabilityReviewRuleDto extends ApiResponseDto
{
    public int $id;

    public int $weekday;

    public string $startTime;

    public string $endTime;

    public ?Carbon $validFrom;

    public ?Carbon $validUntil;

    public ?int $capacityOverride;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                => 'id',
            'weekday'           => 'weekday',
            'start_time'        => 'startTime',
            'end_time'          => 'endTime',
            'valid_from'        => fn (?string $validFrom) => $this->validFrom = is_null($validFrom) ? null : Carbon::parse($validFrom),
            'valid_until'       => fn (?string $validUntil) => $this->validUntil = is_null($validUntil) ? null : Carbon::parse($validUntil),
            'capacity_override' => 'capacityOverride',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewRuleDtoNotConstructableException($msg, previous: $previous);
    }
}
