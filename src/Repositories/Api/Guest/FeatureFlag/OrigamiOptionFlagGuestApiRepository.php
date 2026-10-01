<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\FeatureFlag;

class OrigamiOptionFlagGuestApiRepository extends OrigamiFeatureFlagGuestApiRepository
{
    /**
     * Option flags: the options the operator configures on the marketplace
     */
    protected function getFlagsEndpoint(): string
    {
        return 'option_flags';
    }
}
