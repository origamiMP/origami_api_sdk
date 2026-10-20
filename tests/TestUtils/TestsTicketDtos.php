<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsTicketDtos
{
    protected function mockTicketApiData(array $override = []): array
    {
        $baseData = [
            'id' => TestCase::unique(),
        ];

        return array_merge($baseData, $override);
    }
}
