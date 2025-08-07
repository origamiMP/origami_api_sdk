<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Oauth;

enum GetOauthTokenGrantTypeParamEnum: string
{
    case PASSWORD = 'password';
    case REFRESH = 'refresh_token';
}
