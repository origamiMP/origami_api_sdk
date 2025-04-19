<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api;

use GuzzleHttp\Exception\GuzzleException;
use OrigamiMp\OrigamiApiSdk\Dtos\Oauth\OauthTokenDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Oauth\OauthTokenDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Oauth\GetOauthTokenRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\OrigamiOauthRestClient;

class OrigamiOauthApiRepository extends RestApiRepository
{
    public function __construct(OrigamiOauthRestClient $restClient)
    {
        parent::__construct($restClient);
    }

    /**
     * @throws GuzzleException
     * @throws OauthTokenDtoNotConstructableException
     */
    public function getOauthToken(GetOauthTokenRequestParamBag $paramBag): OauthTokenDto
    {
        if (is_null($paramBag->client_id)) {
            $paramBag->client_id = $this->restClient->getApiClientId();
        }

        if (is_null($paramBag->client_secret)) {
            $paramBag->client_secret = $this->restClient->getApiClientSecret();
        }

        $response = $this->restClient->post('oauth/token', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new OauthTokenDto($responseContent);
    }
}
