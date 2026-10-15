<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldModelValueDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField\CustomFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
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

    /**
     * A custom field as embedded in an entity's `additional_information` (keyed by the custom field `key`): its value
     * along with its config.
     */
    protected function mockCustomFieldModelValueApiData(array $override = []): array
    {
        $baseData = [
            'value'  => 'express',
            'config' => $this->mockCustomFieldModelValueConfigApiData(),
        ];

        return array_merge($baseData, $override);
    }

    protected function mockCustomFieldModelValueDto(array $override = []): CustomFieldModelValueDto
    {
        return new CustomFieldModelValueDto(Obj::fromArray($this->mockCustomFieldModelValueApiData($override)));
    }

    /**
     * The `config` of a custom field embedded in `additional_information`. Unlike includes, its translations are a
     * plain list (no `data` key).
     */
    protected function mockCustomFieldModelValueConfigApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'type'         => CustomFieldDtoTypeEnum::INPUT->value,
            'private'      => false,
            'translations' => [
                ['language_id' => TestCase::unique(), 'name' => 'Delivery time', 'description' => null, 'locale' => 'en'],
            ],
            'group'  => null,
            'params' => null,
        ];

        return array_merge($baseData, $override);
    }

    protected function mockCustomFieldGroupApiData(array $override = []): array
    {
        $baseData = [
            'key'          => 'logistics',
            'translations' => [
                ['language_id' => TestCase::unique(), 'name' => 'Logistics', 'locale' => 'en'],
            ],
        ];

        return array_merge($baseData, $override);
    }
}
