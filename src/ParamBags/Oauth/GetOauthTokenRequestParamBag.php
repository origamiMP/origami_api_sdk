<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\GetOauthTokenGrantTypeParamEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class GetOauthTokenRequestParamBag extends RequestParamBag
{
    public function __construct(
        public GetOauthTokenGrantTypeParamEnum $grant_type,
        public string $username,
        public string $password,
        public ?string $client_id = null,
        public ?string $client_secret = null,
    ) {}

    protected function getJsonRequestParamsList(): array
    {
        return [
            'client_id',
            'client_secret',
            'grant_type',
            'username',
            'password',
        ];
    }
}
