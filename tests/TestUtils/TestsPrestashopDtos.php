<?php

namespace Tests\TestUtils;

use Tests\TestCase;

/**
 * The PrestaShop data is relayed as the shop sends it: ids and flags are strings (`'3'`, `'1'`).
 */
trait TestsPrestashopDtos
{
    protected function mockPrestashopOrderStateApiData(array $override = []): array
    {
        $baseData = [
            'prestashop_id' => (string) TestCase::unique(),
            'color'         => '#4169E1',
            'name'          => 'Awaiting bank wire payment',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockPrestashopShippingZoneApiData(array $override = []): array
    {
        $baseData = [
            'prestashop_id' => (string) TestCase::unique(),
            'name'          => 'Europe',
            'active'        => '1',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockPrestashopTaxApiData(array $override = []): array
    {
        $baseData = [
            'prestashop_id' => (string) TestCase::unique(),
            'name'          => 'FR Taux standard (20%)',
        ];

        return [...$baseData, ...$override];
    }
}
