<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteOpeningHourDtoNotConstructableException;

/**
 * Opening hours of a place on a day of the week (0 for Sunday), as wall-clock times `H:i`.
 */
class ServiceSiteOpeningHourDto extends ApiResponseDto
{
    public int $weekday;

    public string $opensAt;

    public string $closesAt;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'weekday'   => 'weekday',
            'opens_at'  => 'opensAt',
            'closes_at' => 'closesAt',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteOpeningHourDtoNotConstructableException($msg, previous: $previous);
    }
}
