<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Payment;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Payment\PaymentTransactionDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentTransactionDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

/**
 * A payin on a saved card (`cardId`) of an order already placed is charged at once by the payment module, without
 * payment page; the API then requires a `redirectUrl` all the same. The amount is a decimal in the currency.
 */
class CreatePaymentTransactionRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public int $moduleId;

    public PaymentTransactionDtoTypeEnum $type;

    public string $amount;

    public string $currency;

    public int $orderId;

    public int $cardId;

    public string $callbackUrl;

    public string $redirectUrl;

    /** @var array<string, mixed> */
    public array $extraParams;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'moduleId'    => 'module_id',
            'type'        => 'type',
            'amount'      => 'amount',
            'currency'    => 'currency',
            'orderId'     => 'order_id',
            'cardId'      => 'card_id',
            'callbackUrl' => 'callback_url',
            'redirectUrl' => 'redirect_url',
            'extraParams' => 'extra_params',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludesParamsList();
    }

    protected static function getRequestMainDto(): string
    {
        return PaymentTransactionDto::class;
    }
}
