<?php

namespace Tests\Unit\Repositories\Api\Data\Payment;

use OrigamiMp\OrigamiApiSdk\Dtos\Payment\PaymentTransactionDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentTransactionDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentTransactionDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Payment\CreatePaymentTransactionRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Payment\OrigamiPaymentTransactionDataApiRepository;
use Tests\TestCase;

class OrigamiPaymentTransactionDataApiRepositoryTest extends TestCase
{
    /**
     * `POST payments/transactions` should send a payin on a saved card of an order, and read its state.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockPaymentTransactionApiData(['id' => 42])]));
        $paramBag = new CreatePaymentTransactionRequestParamBag;
        $paramBag->moduleId = 3;
        $paramBag->type = PaymentTransactionDtoTypeEnum::PAYIN;
        $paramBag->amount = '25.00';
        $paramBag->currency = 'EUR';
        $paramBag->orderId = 5;
        $paramBag->cardId = 7;
        $paramBag->redirectUrl = 'https://example.com/appointments';
        $paramBag->extraParams = ['service_booking_id' => 11];

        $transaction = (new OrigamiPaymentTransactionDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'payments/transactions');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertLastApiRequestJsonWas([
            'module_id'    => 3,
            'type'         => 'PAYIN',
            'amount'       => '25.00',
            'currency'     => 'EUR',
            'order_id'     => 5,
            'card_id'      => 7,
            'redirect_url' => 'https://example.com/appointments',
            'extra_params' => ['service_booking_id' => 11],
        ]);
        $this->assertInstanceOf(PaymentTransactionDto::class, $transaction);
        $this->assertSame(42, $transaction->id);
        $this->assertSame(PaymentTransactionDtoStateEnum::SUCCESS, $transaction->state);
        $this->assertSame('25.00', $transaction->amount);
    }
}
