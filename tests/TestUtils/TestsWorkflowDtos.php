<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsWorkflowDtos
{
    protected function mockStateApiData(array $override = []): array
    {
        $baseData = [
            'id'          => TestCase::unique(),
            'key'         => 'service_booking_confirmed',
            'color'       => '#2e7d32',
            'initial'     => false,
            'is_final'    => false,
            'readonly'    => true,
            'workflow_id' => TestCase::unique(),
            'text_color'  => '#ffffff',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockStateTranslationApiData(array $override = []): array
    {
        $baseData = [
            'name'        => 'Confirmed',
            'action_name' => null,
            'description' => null,
            'default'     => true,
            'locale'      => 'en',
            'language_id' => TestCase::unique(),
        ];

        return [...$baseData, ...$override];
    }

    protected function mockStateDto(array $override = []): StateDto
    {
        return new StateDto(Obj::fromArray($this->mockStateApiData($override)));
    }
}
