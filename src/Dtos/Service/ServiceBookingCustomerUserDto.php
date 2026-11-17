<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingCustomerUserDtoNotConstructableException;

/**
 * The person of the customer account who made the booking.
 */
class ServiceBookingCustomerUserDto extends ApiResponseDto
{
    public int $id;

    public string $name;

    public ?string $email;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'    => 'id',
            'name'  => 'name',
            'email' => 'email',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceBookingCustomerUserDtoNotConstructableException($msg, previous: $previous);
    }
}
