<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OptionFlag;

use OrigamiMp\OrigamiApiSdk\Dtos\OptionFlag\OptionFlagListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\OptionFlag\OptionFlagListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiOptionFlagGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Get the feature flags exposed by the API (public endpoint)
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OptionFlagListDtoNotConstructableException
     */
    public function list(): OptionFlagListDto
    {
        $response = $this->restClient->get('option_flags');
        $responseContent = json_decode($response->getBody()->getContents());

        return new OptionFlagListDto($responseContent);
    }
}
