<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Language\LanguageDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsLanguageDtos
{
    protected function mockLanguageApiData(array $override = []): array
    {
        $baseData = [
            'id'         => TestCase::unique(),
            'locale'     => 'fr',
            'name'       => 'Français',
            'is_default' => false,
            'is_active'  => true,
        ];

        return array_merge($baseData, $override);
    }

    protected function mockLanguageDto(array $override = []): LanguageDto
    {
        return new LanguageDto(Obj::fromArray($this->mockLanguageApiData($override)));
    }
}
