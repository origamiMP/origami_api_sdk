<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingCustomerDtoNotConstructableException;

/**
 * The customer account of a booking. Its email and the one of its user are null when the current user may not see them.
 */
class ServiceBookingCustomerDto extends ApiResponseDto
{
    public int $id;

    public ?string $name;

    public ?string $email;

    public ?string $phone;

    public ?ServiceBookingCustomerUserDto $user;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'    => 'id',
            'name'  => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'user'  => fn (?object $user) => $this->user = is_null($user) ? null : new ServiceBookingCustomerUserDto($user),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceBookingCustomerDtoNotConstructableException($msg, previous: $previous);
    }
}
