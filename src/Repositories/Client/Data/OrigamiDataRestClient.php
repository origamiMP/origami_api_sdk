<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client\Data;

use OrigamiMp\OrigamiApiSdk\Contracts\Dtos\Oauth\OauthToken;
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

    /**
     * Locale the API answers in (error messages, among others): its default language without it.
     */
    private ?string $locale;

    public function __construct(string $apiUri, OauthToken $oauthToken, ?int $userGroupId = null, ?string $locale = null)
    {
        parent::__construct($apiUri);

        $this->oauthToken = $oauthToken;
        $this->userGroupId = $userGroupId;
        $this->locale = $locale;
    }

    public function getOauthToken(): OauthToken
    {
        return $this->oauthToken;
    }

    public function getUserGroupId(): ?int
    {
        return $this->userGroupId;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    protected function getAdditionalHeaders(): array
    {
        return array_merge(
            parent::getAdditionalHeaders(),
            $this->getAuthentificationHeaders(),
            is_null($this->locale) ? [] : ['Accept-Language' => $this->locale],
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
}
