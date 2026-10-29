<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Order\OrderLineDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsOrderDtos
{
    protected function mockQuoteSellerApiData(array $override = []): array
    {
        $baseData = [
            'id'         => TestCase::unique(),
            'reference'  => 'WZXIIYTYX',
            'order_id'   => TestCase::unique(),
            'created_at' => '2024-05-12 16:12:56',
            'updated_at' => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    /**
     * A line without answer: add a `form_submission` include to get one.
     */
    protected function mockOrderLineApiData(array $override = []): array
    {
        $baseData = [
            'id' => TestCase::unique(),
        ];

        return [...$baseData, ...$override];
    }

    protected function mockOrderLineDto(array $override = []): OrderLineDto
    {
        return new OrderLineDto(Obj::fromArray($this->mockOrderLineApiData($override)));
    }
}
