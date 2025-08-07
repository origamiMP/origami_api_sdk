<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class SellerListDto extends ApiResponseDto
{
    use HasAvailableIncludes, HasPagination;

    protected static array $availableIncludes = [
        // 'shipping_offers',
        // 'product_offers',
        // 'tickets',
        // 'documents',
        // 'default_tax',
        // 'payment_reports',
        // 'bank_accounts',
        // 'invoices',
        // 'subscriptions',
        // 'legal_information',
        // 'psp_wallets',
        // 'required_documents',
        // 'visibilities',
        // 'users',
        // 'user_group_users',
        // 'reviews',
        // 'psp_users',
        // 'notifications_sent',
        // 'invoice_tax',
        // 'psp_ubos',
        // 'subscription_lines',
        // 'warehouses',
        // 'translations',
    ];

    protected function getDefaultDataStructureToProperties(): array
    {
        return $this->getPaginationAsDataStructureToProperties();
    }

    protected function validationRulesForProperties(): array
    {
        return $this->getPaginationValidationRules();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new SellerListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($seller) => new SellerDto($seller));
    }
}
