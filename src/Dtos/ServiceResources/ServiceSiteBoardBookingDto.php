<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardBookingDtoNotConstructableException;

class ServiceSiteBoardBookingDto extends ApiResponseDto
{
    public int $id;

    public ServiceBookingDtoStatusEnum $status;

    public ?int $stateId;

    public int $quantity;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'       => 'id',
            'status'   => fn ($status) => $this->status = ServiceBookingDtoStatusEnum::from($status),
            'state_id' => 'stateId',
            'quantity' => 'quantity',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteBoardBookingDtoNotConstructableException($msg, previous: $previous);
    }
}
