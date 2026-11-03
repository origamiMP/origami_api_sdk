<?php

namespace Tests\Unit\Dtos\FeatureFlag;

use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\FeatureFlag\FeatureFlagListDtoNotConstructableException;
use Tests\TestCase;

class FeatureFlagListDtoTest extends TestCase
{
    /**
     * A flag whose state is not a boolean should make the Dto not constructable.
     */
    public function test_flags_case_1(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->mockFeatureFlagListDto(['some_flag' => 'yes']),
            FeatureFlagListDtoNotConstructableException::class,
            "The state of the flag 'some_flag' is not a boolean.",
        );
    }
}
