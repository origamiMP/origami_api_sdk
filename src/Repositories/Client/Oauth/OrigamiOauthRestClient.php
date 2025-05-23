<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client\Oauth;

use OrigamiMp\OrigamiApiSdk\Repositories\Client\OrigamiRestClient;

class OrigamiOauthRestClient extends OrigamiRestClient
{
    /**
     * ID of the API auth client. Supplied by Origami.
     */
    private string $apiClientId;

    /**
     * Secret of the API auth client. Supplied by Origami.
     */
    private string $apiClientSecret;

    public function __construct(string $apiUri, string $apiClientId, string $apiClientSecret)
    {
        parent::__construct($apiUri);

        $this->apiClientId = $apiClientId;
        $this->apiClientSecret = $apiClientSecret;
    }

    protected function getResourcePrefix(): string
    {
        return '';
    }

    public function getApiClientId(): string
    {
        return $this->apiClientId;
    }

    public function getApiClientSecret(): string
    {
        return $this->apiClientSecret;
    }
}
