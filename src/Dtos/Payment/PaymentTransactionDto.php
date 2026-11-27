<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Payment;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentTransactionDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentTransactionDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Payment\PaymentTransactionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

/**
 * A payment, or a movement of money between the accounts of the marketplace. Its amount is a decimal in the currency,
 * not in cents.
 */
class PaymentTransactionDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public PaymentTransactionDtoTypeEnum $type;

    public ?string $pspTransactionId;

    public string $amount;

    public string $currency;

    public PaymentTransactionDtoStateEnum $state;

    public ?string $paymentPageUrl;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'psp',
            // 'parent',
            // 'order_sellers',
            // 'children',
            // 'orders',
            // 'paymentReport',
            // 'gift_card_usage',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                 => 'id',
            'type'               => fn ($type) => $this->type = PaymentTransactionDtoTypeEnum::from($type),
            'psp_transaction_id' => 'pspTransactionId',
            'amount'             => 'amount',
            'currency'           => 'currency',
            'state'              => fn ($state) => $this->state = PaymentTransactionDtoStateEnum::from($state),
            'payment_page_url'   => 'paymentPageUrl',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new PaymentTransactionDtoNotConstructableException($msg, previous: $previous);
    }
}
