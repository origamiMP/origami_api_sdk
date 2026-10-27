<?php

namespace Tests\TestUtils;

trait TestsConfigurationDtos
{
    protected function mockConfigurationApiData(array $override = []): array
    {
        $baseData = [
            'key'   => 'general_marketplace_name',
            'value' => 'My marketplace',
        ];

        return array_merge($baseData, $override);
    }
}
