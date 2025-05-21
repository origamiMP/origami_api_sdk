<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Oauth;

use GuzzleHttp\Exception\GuzzleException;
use OrigamiMp\OrigamiApiSdk\Dtos\Oauth\OauthTokenDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Oauth\OauthTokenDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Oauth\GetOauthTokenRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\RestApiRepository;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\Oauth\OrigamiOauthRestClient;

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
        if (! isset($paramBag->clientId)) {
            $paramBag->clientId = $this->restClient->getApiClientId();
        }

        if (! isset($paramBag->clientSecret)) {
            $paramBag->clientSecret = $this->restClient->getApiClientSecret();
        }

        $response = $this->restClient->post('oauth/token', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new OauthTokenDto($responseContent);
    }
}
