<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Oauth\GetOauthTokenParamBagGrantTypeEnum;

class GetOauthTokenWithRefreshTokenRequestParamBag extends GetOauthTokenRequestParamBag
{
    /**
     * @var string The refresh token obtained during a previous GetOauthTokenRequest.
     */
    public string $refreshToken;

    public function __construct()
    {
        parent::__construct(GetOauthTokenParamBagGrantTypeEnum::REFRESH);
    }

    protected function getJsonRequestParamsList(): array
    {
        return array_merge(
            parent::getJsonRequestParamsList(),
            [
                'refreshToken',
            ]
        );
    }
}
