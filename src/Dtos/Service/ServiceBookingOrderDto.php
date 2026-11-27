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

    /** The order holding the order of the seller, a payment being made on it; null from an API that does not send it yet. */
    public ?int $orderId = null;

    public ?string $reference;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'        => 'id',
            'order_id'  => 'orderId',
            'reference' => 'reference',
        ];
    }

    protected function getOptionalKeys(): array
    {
        return [
            'order_id',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceBookingOrderDtoNotConstructableException($msg, previous: $previous);
    }
}
