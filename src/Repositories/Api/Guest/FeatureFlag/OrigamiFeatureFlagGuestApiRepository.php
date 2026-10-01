<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\FeatureFlag;

use OrigamiMp\OrigamiApiSdk\Dtos\FeatureFlag\FeatureFlagListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\FeatureFlag\FeatureFlagListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

abstract class OrigamiFeatureFlagGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Get the flags of this kind exposed by the API (public endpoint), as a flat name => state map
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FeatureFlagListDtoNotConstructableException
     */
    public function list(): FeatureFlagListDto
    {
        $response = $this->restClient->get($this->getFlagsEndpoint());
        $responseContent = json_decode($response->getBody()->getContents());

        return new FeatureFlagListDto($responseContent);
    }

    abstract protected function getFlagsEndpoint(): string;
}
