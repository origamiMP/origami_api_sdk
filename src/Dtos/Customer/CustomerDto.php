<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Customer;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

/**
 * The customer of an order, as included in it: only what names it, since its other fields (email, address…) may be
 * empty for a customer.
 */
class CustomerDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public ?string $name = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'bank_accounts',
            // 'customer_groups',
            // 'documents',
            // 'invoices',
            // 'legal_information',
            // 'mandates',
            // 'order_sellers',
            // 'order_addresses',
            // 'payment_reports',
            // 'product_offers',
            // 'psp_wallets',
            // 'shipping_offers',
            // 'subscriptions',
            // 'tickets',
            // 'users',
            // 'user_group_users',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'        => 'id',
            'name'      => 'name',
            'firstname' => 'firstName',
            'lastname'  => 'lastName',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'        => ['required', 'integer'],
            'name'      => ['nullable', 'string'],
            'firstname' => ['nullable', 'string'],
            'lastname'  => ['nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomerDtoNotConstructableException($msg, previous: $previous);
    }
}
