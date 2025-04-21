<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\GetOauthTokenGrantTypeParamEnum;

class GetOauthTokenWithRefreshTokenRequestParamBag extends GetOauthTokenRequestParamBag
{
    public string $refresh_token;

    /**
     * @param  string  $refresh_token  The refresh token obtained during a previous GetOauthTokenRequest.
     */
    public function __construct(
        string $refresh_token,
    ) {
        parent::__construct(GetOauthTokenGrantTypeParamEnum::REFRESH);

        $this->refresh_token = $refresh_token;
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
