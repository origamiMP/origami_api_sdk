<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Configuration;

use OrigamiMp\OrigamiApiSdk\Dtos\Configuration\ConfigurationDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Configuration\ConfigurationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiConfigurationDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ConfigurationDtoNotConstructableException
     */
    public function get(string $key): ConfigurationDto
    {
        $response = $this->restClient->get("configurations/{$key}");
        $responseContent = $this->decodeResponse($response);

        return new ConfigurationDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }
}
