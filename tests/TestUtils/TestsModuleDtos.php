<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleLogDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Module\ModuleLogDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsModuleDtos
{
    protected function mockModuleApiData(array $override = []): array
    {
        $baseData = [
            'id'              => TestCase::unique(),
            'name'            => 'payment',
            'current_version' => '1.0.0',
            // Associative (not a list) : encoded as a JSON object, so it decodes back into a stdClass
            // to match ModuleDto's typed `object` properties.
            'configurations'           => ['is_enabled' => true],
            'type'                     => 'payment',
            'version'                  => '1.0.0',
            'author'                   => 'Origami',
            'display_name'             => 'Payment',
            'description'              => 'Payment module.',
            'image'                    => 'https://example.com/module.png',
            'available_configurations' => ['is_enabled' => true],
            'is_active'                => true,
            'created_at'               => '2024-05-12 16:12:56',
            'updated_at'               => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockModuleLogApiData(array $override = []): array
    {
        $baseData = [
            'id'   => TestCase::unique(),
            'type' => ModuleLogDtoTypeEnum::ERROR->value,
            // Associative (not a list) : encoded as a JSON object, so it decodes back into a stdClass.
            'log'        => ['message' => 'Unable to reach the PSP', 'order_id' => TestCase::unique()],
            'created_at' => '2024-05-12 16:12:56',
            'updated_at' => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockModuleLogDto(array $override = []): ModuleLogDto
    {
        return new ModuleLogDto(Obj::fromArray($this->mockModuleLogApiData($override)));
    }
}
