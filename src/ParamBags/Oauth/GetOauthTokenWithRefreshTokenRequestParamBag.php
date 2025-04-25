<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\GetOauthTokenGrantTypeParamEnum;

class GetOauthTokenWithRefreshTokenRequestParamBag extends GetOauthTokenRequestParamBag
{
    /**
     * @var string The refresh token obtained during a previous GetOauthTokenRequest.
     */
    public string $refresh_token;

    public function __construct()
    {
        parent::__construct(GetOauthTokenGrantTypeParamEnum::REFRESH);
    }

    protected function getJsonRequestParamsList(): array
    {
        return array_merge(
            parent::getJsonRequestParamsList(),
            [
                'refresh_token',
            ]
        );
    }
}
