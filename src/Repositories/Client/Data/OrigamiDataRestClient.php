<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client\Data;

use OrigamiMp\OrigamiApiSdk\Contracts\Dtos\Oauth\OauthToken as OauthTokenContract;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\OrigamiRestClient;

class OrigamiDataRestClient extends OrigamiRestClient
{
    /**
     * Oauth token that will be used for authenticating a certain User.
     */
    private OauthTokenContract $oauthToken;

    /**
     * Id of the UserGroup that should be used for authentication.
     */
    private ?int $userGroupId;

    public function __construct(string $apiUri, OauthTokenContract $oauthToken, ?int $userGroupId = null)
    {
        parent::__construct($apiUri);

        $this->oauthToken = $oauthToken;
        $this->userGroupId = $userGroupId;
    }

    public function getOauthToken(): OauthTokenContract
    {
        return $this->oauthToken;
    }

    public function getUserGroupId(): ?int
    {
        return $this->userGroupId;
    }

    protected function getAdditionalHeaders(): array
    {
        return [
            ...parent::getAdditionalHeaders(),
            ...$this->getAuthenticationHeaders(),
        ];
    }

    protected function getAuthenticationHeaders(): array
    {
        $headers = [
            'Authorization' => "Bearer {$this->oauthToken->getAccessToken()}",
        ];

        if (! is_null($this->userGroupId)) {
            $headers['context'] = $this->userGroupId;
        }

        return $headers;
    }
}
