<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Oauth\GetOauthTokenParamBagGrantTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

abstract class GetOauthTokenRequestParamBag extends RequestParamBag
{
    public GetOauthTokenParamBagGrantTypeEnum $grantType;

    public string $clientId;

    public string $clientSecret;

    public function __construct(
        GetOauthTokenParamBagGrantTypeEnum $grantType,
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
