<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client\Data;

use OrigamiMp\OrigamiApiSdk\Contracts\OauthToken;
use OrigamiMp\OrigamiApiSdk\Enums\Http\HttpRequestMethodEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\OrigamiRestClient;

class OrigamiDataRestClient extends OrigamiRestClient
{
    /**
     * Oauth token that will be used for authenticating a certain User.
     */
    private OauthToken $oauthToken;

    /**
     * Id of the UserGroup that should be used for authentication.
     */
    private ?int $userGroupId;

    public function __construct(string $apiUri, OauthToken $oauthToken, ?int $userGroupId = null)
    {
        parent::__construct($apiUri);

        $this->oauthToken = $oauthToken;
        $this->userGroupId = $userGroupId;
    }

    public function getoauthToken(): OauthToken
    {
        return $this->oauthToken;
    }

    public function getUserGroupId(): ?int
    {
        return $this->userGroupId;
    }

    protected function getGuzzleParamsForRequest(HttpRequestMethodEnum $method, ?RequestParamBag $paramBag): array
    {
        $guzzleParamsFromParamBag = $paramBag?->asGuzzleParams() ?? [];

        return $this->mergeAdditionalHeadersInGuzzleParams($guzzleParamsFromParamBag);
    }

    protected function getResourcePrefix(): string
    {
        return 'v1';
    }

    protected function getAdditionalHeaders(): array
    {
        return array_merge(
            parent::getAdditionalHeaders(),
            $this->getAuthentificationHeaders(),
        );
    }

    protected function getAuthentificationHeaders(): array
    {
        $headers = [
            'Authorization' => "Bearer {$this->oauthToken->getAccessToken()}",
        ];

        if (! is_null($this->userGroupId)) {
            $headers['context'] = $this->userGroupId;
        }

        return $headers;
    }

    // TODO DEV : Auto-use of refresh token if 401 (configurable) ?
}
