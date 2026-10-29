<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Dtos\Oauth;

use Carbon\Carbon;

/**
 * Oauth token authenticating the requests of the Data API, implemented by `OauthTokenDto` or by the application
 * keeping the token elsewhere (in session).
 */
interface OauthToken
{
    public function getTokenType(): string;

    public function getAccessToken(): string;

    public function getRefreshToken(): string;

    public function getExpiresAt(): Carbon;
}
