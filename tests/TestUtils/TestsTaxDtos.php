<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Taxes\TaxDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Integer;

trait TestsTaxDtos
{
    protected function mockTaxApiData(array $override = []): array
    {
        $baseData = [
            'id'           => Integer::unique(),
            'value'        => 20.0,
            'type'         => TaxDtoTypeEnum::PERCENTAGE->value,
            'id_scs_tax'   => null,
            'is_default'   => false,
            'module_id'    => null,
            'translations' => ['data' => [
                ['locale' => 'en', 'default' => true, 'language_id' => Integer::unique(), 'name' => 'VAT'],
            ]],
        ];

        return array_merge($baseData, $override);
    }
}
