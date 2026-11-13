<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingSellerDtoNotConstructableException;

class ServiceBookingSellerDto extends ApiResponseDto
{
    public int $id;

    public ?string $name;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'   => 'id',
            'name' => 'name',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceBookingSellerDtoNotConstructableException($msg, previous: $previous);
    }
}
