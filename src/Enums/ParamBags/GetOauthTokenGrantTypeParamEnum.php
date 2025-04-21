<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\ParamBags;

enum GetOauthTokenGrantTypeParamEnum: string
{
    case PASSWORD = 'password';
    case REFRESH = 'refresh_token';
}
