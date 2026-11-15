<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsAddressDtos
{
    protected function mockAddressApiData(array $override = []): array
    {
        $baseData = [
            'id'            => TestCase::unique(),
            'street1'       => '10 rue de Rivoli',
            'street2'       => null,
            'address_line1' => '10 rue de Rivoli',
            'address_line2' => null,
            'postcode'      => '75004',
            'city'          => 'Paris',
            'country'       => 'France',
            'country_iso'   => 'FR',
            'latitude'      => null,
            'longitude'     => null,
            'region'        => null,
        ];

        return [...$baseData, ...$override];
    }
}
