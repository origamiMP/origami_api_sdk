<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Helpers\Support\Integer;

trait TestsCountryDtos
{
    protected function mockCountryApiData(array $override = []): array
    {
        $baseData = [
            'id'    => Integer::unique(),
            'name'  => 'France',
            'iso_2' => 'FR',
        ];

        return array_merge($baseData, $override);
    }
}
