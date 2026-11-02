<?php

namespace Tests\TestUtils;

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
}
