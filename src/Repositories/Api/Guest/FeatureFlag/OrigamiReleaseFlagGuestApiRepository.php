<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\FeatureFlag;

use OrigamiMp\OrigamiApiSdk\Dtos\FeatureFlag\FeatureFlagListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\FeatureFlag\FeatureFlagListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiReleaseFlagGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Get the release flags of the API's own features and breaking changes, as a flat name => state map
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FeatureFlagListDtoNotConstructableException
     */
    public function list(): FeatureFlagListDto
    {
        $response = $this->restClient->get('release_flags');
        $responseContent = json_decode($response->getBody()->getContents());

        return new FeatureFlagListDto($responseContent);
    }
}
