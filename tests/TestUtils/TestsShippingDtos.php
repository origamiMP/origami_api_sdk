<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsShippingDtos
{
    protected function mockShippingZoneApiData(array $override = []): array
    {
        $baseData = [
            'id'         => TestCase::unique(),
            'postcodes'  => null,
            'created_at' => '2024-05-12 16:12:56',
            'updated_at' => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockShippingZoneTranslationApiData(array $override = []): array
    {
        $baseData = [
            'locale'      => 'en',
            'default'     => true,
            'language_id' => TestCase::unique(),
            'name'        => 'Europe',
        ];

        return [...$baseData, ...$override];
    }
}
