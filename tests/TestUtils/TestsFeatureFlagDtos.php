<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\FeatureFlag\FeatureFlagListDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;

trait TestsFeatureFlagDtos
{
    protected function mockFeatureFlagListApiData(array $override = []): array
    {
        $baseData = [
            'some_flag' => true,
            'other'     => false,
        ];

        return [...$baseData, ...$override];
    }

    protected function mockFeatureFlagListDto(array $override = []): FeatureFlagListDto
    {
        return new FeatureFlagListDto(Obj::fromArray($this->mockFeatureFlagListApiData($override)));
    }
}
