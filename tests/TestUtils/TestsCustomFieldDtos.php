<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField\CustomFieldDtoTypeEnum;
use Tests\TestCase;

trait TestsCustomFieldDtos
{
    protected function mockCustomFieldApiData(array $override = []): array
    {
        $baseData = [
            'id'                    => TestCase::unique(),
            'key'                   => 'delivery_time',
            'type'                  => CustomFieldDtoTypeEnum::INPUT->value,
            'model_type'            => 'seller',
            'custom_field_group_id' => null,
            'params'                => null,
            'private'               => false,
            'translations'          => ['data' => [
                ['locale' => 'en', 'language_id' => TestCase::unique(), 'name' => 'Delivery time', 'description' => null],
            ]],
            'created_at' => '2024-05-12 16:12:56',
            'updated_at' => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }

    protected function mockCustomFieldValueApiData(array $override = []): array
    {
        $baseData = [
            'id'              => TestCase::unique(),
            'value'           => 'express',
            'custom_field_id' => TestCase::unique(),
            'created_at'      => '2024-05-12 16:12:56',
            'updated_at'      => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }
}
