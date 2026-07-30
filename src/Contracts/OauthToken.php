<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts;

use Carbon\Carbon;

// TODO Contract : move to Dto/Oauth directory

interface OauthToken
{
    public function getTokenType(): string;

    public function getAccessToken(): string;

    public function getRefreshToken(): string;

    public function getExpiresAt(): Carbon;
}
