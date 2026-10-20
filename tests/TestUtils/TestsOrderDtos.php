<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Order\OrderLineDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsOrderDtos
{
    /**
     * Only the fields the SDK reads: the API sends many more.
     */
    protected function mockQuoteSellerApiData(array $override = []): array
    {
        $baseData = [
            'id'         => TestCase::unique(),
            'reference'  => 'WZXIIYTYX',
            'type'       => 'quote',
            'order_id'   => TestCase::unique(),
            'state_id'   => TestCase::unique(),
            'created_at' => '2024-05-12 16:12:56',
            'updated_at' => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }

    /**
     * Without `form_submission_id`, which the API only sends while the form_builder option is on.
     */
    protected function mockOrderLineApiData(array $override = []): array
    {
        $baseData = [
            'id'              => TestCase::unique(),
            'order_seller_id' => TestCase::unique(),
            'product_id'      => TestCase::unique(),
            'name'            => 'Cleaning service',
            'quantity'        => 1,
        ];

        return array_merge($baseData, $override);
    }

    protected function mockOrderLineDto(array $override = []): OrderLineDto
    {
        return new OrderLineDto(Obj::fromArray($this->mockOrderLineApiData($override)));
    }
}
