<?php

namespace Tests\Unit\Repositories\Api\Guest\FeatureFlag;

use OrigamiMp\OrigamiApiSdk\Dtos\FeatureFlag\FeatureFlagListDto;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\FeatureFlag\OrigamiOptionFlagGuestApiRepository;
use Tests\TestCase;

class OrigamiOptionFlagGuestApiRepositoryTest extends TestCase
{
    /**
     * `GET option_flags` answers a flat object (no `data` key): each of its keys should be read as a flag,
     * and an unknown flag should be reported as inactive.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse(['some_flag' => true, 'other' => false]));

        $flags = (new OrigamiOptionFlagGuestApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'option_flags');
        $this->assertInstanceOf(FeatureFlagListDto::class, $flags);
        $this->assertTrue($flags->isFlagActive('some_flag'));
        $this->assertFalse($flags->isFlagActive('other'));
        $this->assertFalse($flags->isFlagActive('unknown'));
    }
}
