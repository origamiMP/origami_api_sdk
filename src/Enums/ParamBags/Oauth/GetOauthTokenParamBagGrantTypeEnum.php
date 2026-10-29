<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Oauth;

enum GetOauthTokenParamBagGrantTypeEnum: string
{
    case PASSWORD = 'password';
    case REFRESH_TOKEN = 'refresh_token';
}
