<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingOrderDtoNotConstructableException;

/**
 * The order of a booking at the seller of its offer.
 */
class ServiceBookingOrderDto extends ApiResponseDto
{
    public int $id;

    public ?string $reference;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'        => 'id',
            'reference' => 'reference',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceBookingOrderDtoNotConstructableException($msg, previous: $previous);
    }
}
