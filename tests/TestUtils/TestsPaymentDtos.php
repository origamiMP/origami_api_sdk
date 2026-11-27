<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentPspCardDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentTransactionDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentTransactionDtoTypeEnum;
use Tests\TestCase;

trait TestsPaymentDtos
{
    /**
     * The API also sends the id of the card at the provider, its public key and its uuid, not read by the DTO.
     */
    protected function mockPaymentPspCardApiData(array $override = []): array
    {
        $baseData = [
            'id'             => TestCase::unique(),
            'name'           => null,
            'user_group_id'  => TestCase::unique(),
            'payment_psp_id' => TestCase::unique(),
            'state'          => PaymentPspCardDtoStateEnum::VALIDATED->value,
            'brand'          => 'visa',
            'last4'          => '4242',
            'created_at'     => '2024-05-12 16:12:56',
            'updated_at'     => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    /**
     * The API sends every key of the transaction (its provider, dates, extra information); only those read by the DTO
     * are here.
     */
    protected function mockPaymentTransactionApiData(array $override = []): array
    {
        $baseData = [
            'id'                 => TestCase::unique(),
            'type'               => PaymentTransactionDtoTypeEnum::PAYIN->value,
            'psp_transaction_id' => 'pi_3QhXy2Lk',
            'amount'             => '25.00',
            'currency'           => 'EUR',
            'state'              => PaymentTransactionDtoStateEnum::SUCCESS->value,
            'payment_page_url'   => null,
        ];

        return [...$baseData, ...$override];
    }
}
