<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\FeatureFlag;

class OrigamiReleaseFlagGuestApiRepository extends OrigamiFeatureFlagGuestApiRepository
{
    /**
     * Release flags: the progressive rollouts of the API's own features and breaking changes
     */
    protected function getFlagsEndpoint(): string
    {
        return 'release_flags';
    }
}
