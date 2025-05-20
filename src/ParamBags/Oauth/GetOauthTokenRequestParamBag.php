<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\GetOauthTokenGrantTypeParamEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

abstract class GetOauthTokenRequestParamBag extends RequestParamBag
{
    public GetOauthTokenGrantTypeParamEnum $grantType;

    public string $clientId;

    public string $clientSecret;

    public function __construct(
        GetOauthTokenGrantTypeParamEnum $grantType,
    ) {
        $this->grantType = $grantType;
    }

    protected function getJsonRequestParamsList(): array
    {
        return [
            'clientId',
            'clientSecret',
            'grantType',
        ];
    }
}
