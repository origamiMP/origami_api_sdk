<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceCreateSeriesSkippedDtoNotConstructableException;

/**
 * A date of a series left without presence, with the error code telling why: `130108` the resource is taken, `130109`
 * the provider is already present elsewhere, `130110` the resource is booked.
 */
class ServicePresenceCreateSeriesSkippedDto extends ApiResponseDto
{
    /** Y-m-d. */
    public string $date;

    public string $code;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'date' => 'date',
            'code' => 'code',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServicePresenceCreateSeriesSkippedDtoNotConstructableException($msg, previous: $previous);
    }
}
