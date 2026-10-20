<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsTicketDtos
{
    protected function mockTicketApiData(array $override = []): array
    {
        $baseData = [
            'id'                   => TestCase::unique(),
            'external_uid'         => null,
            'subject'              => 'Quote WZXIIYTYX',
            'operator_assigned_id' => null,
            'seller_id'            => TestCase::unique(),
            'customer_id'          => TestCase::unique(),
            'product_id'           => null,
            'order_seller_id'      => TestCase::unique(),
            'status'               => 'open',
            'created_at'           => '2024-05-12 16:12:56',
            'updated_at'           => '2024-05-12 16:12:56',
            'created_by'           => TestCase::unique(),
        ];

        return array_merge($baseData, $override);
    }
}
