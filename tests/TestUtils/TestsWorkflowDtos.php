<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsWorkflowDtos
{
    protected function mockStateApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'key'          => 'service_booking_confirmed',
            'color'        => '#2e7d32',
            'initial'      => false,
            'is_final'     => false,
            'readonly'     => true,
            'workflow_id'  => TestCase::unique(),
            'text_color'   => '#ffffff',
            'name'         => 'Confirmed',
            'action_name'  => null,
            'description'  => null,
            'translations' => ['data' => []],
        ];

        return [...$baseData, ...$override];
    }
}
