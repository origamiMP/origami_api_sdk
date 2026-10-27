<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Taxes\TaxDtoTypeEnum;
use Tests\TestCase;

trait TestsTaxDtos
{
    protected function mockTaxApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'value'        => 20.0,
            'type'         => TaxDtoTypeEnum::PERCENTAGE->value,
            'id_scs_tax'   => null,
            'is_default'   => false,
            'module_id'    => null,
            'translations' => ['data' => [
                ['locale' => 'en', 'default' => true, 'language_id' => TestCase::unique(), 'name' => 'VAT'],
            ]],
        ];

        return [...$baseData, ...$override];
    }
}
