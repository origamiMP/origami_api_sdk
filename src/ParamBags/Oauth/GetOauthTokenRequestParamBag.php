<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\GetOauthTokenGrantTypeParamEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

abstract class GetOauthTokenRequestParamBag extends RequestParamBag
{
    public GetOauthTokenGrantTypeParamEnum $grant_type;

    public string $client_id;

    public string $client_secret;

    public function __construct(
        GetOauthTokenGrantTypeParamEnum $grant_type,
    ) {
        $this->grant_type = $grant_type;
    }

    protected function getJsonRequestParamsList(): array
    {
        return [
            'client_id',
            'client_secret',
            'grant_type',
        ];
    }
}
