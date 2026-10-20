<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Ticket;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Ticket\TicketDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class TicketDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'messages',
            // 'seller',
            // 'order_seller',
            // 'operator_assigned',
            // 'seller_assigned',
            // 'product',
            // 'reports',
            // 'tags',
            // 'customer',
            // 'type',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id' => 'id',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id' => ['required', 'integer'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new TicketDtoNotConstructableException($msg, previous: $previous);
    }
}
