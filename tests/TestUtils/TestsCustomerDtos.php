<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsCustomerDtos
{
    protected function mockCustomerApiData(array $override = []): array
    {
        $baseData = [
            'id' => TestCase::unique(),
        ];

        return [...$baseData, ...$override];
    }
}
