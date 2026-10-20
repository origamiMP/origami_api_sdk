<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Ticket;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Ticket\TicketDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class TicketDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public string $subject;

    public mixed $status;

    public ?int $sellerId;

    public ?int $customerId;

    public ?int $orderSellerId;

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
        $structure = [
            'id'              => 'id',
            'subject'         => 'subject',
            'status'          => 'status',
            'seller_id'       => 'sellerId',
            'customer_id'     => 'customerId',
            'order_seller_id' => 'orderSellerId',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'              => ['required', 'integer'],
            'subject'         => ['required', 'string'],
            'status'          => ['present'],
            'seller_id'       => ['present', 'nullable', 'integer'],
            'customer_id'     => ['present', 'nullable', 'integer'],
            'order_seller_id' => ['present', 'nullable', 'integer'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new TicketDtoNotConstructableException($msg, previous: $previous);
    }
}
