<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsCountryDtos
{
    protected function mockCountryApiData(array $override = []): array
    {
        $baseData = [
            'id'    => TestCase::unique(),
            'name'  => 'France',
            'iso_2' => 'FR',
        ];

        return [...$baseData, ...$override];
    }
}
