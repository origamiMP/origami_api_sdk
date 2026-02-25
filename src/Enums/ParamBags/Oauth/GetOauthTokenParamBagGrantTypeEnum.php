<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Oauth;

enum GetOauthTokenParamBagGrantTypeEnum: string
{
    case PASSWORD = 'password';
    case REFRESH = 'refresh_token';
}
