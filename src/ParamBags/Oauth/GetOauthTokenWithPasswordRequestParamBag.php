<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\GetOauthTokenGrantTypeParamEnum;

class GetOauthTokenWithPasswordRequestParamBag extends GetOauthTokenRequestParamBag
{
    /**
     * @var string Username of the user you want to log in.
     */
    public string $username;

    /**
     * @var string Password of the user you want to log in.
     */
    public string $password;

    public function __construct()
    {
        parent::__construct(GetOauthTokenGrantTypeParamEnum::PASSWORD);
    }

    protected function getJsonRequestParamsList(): array
    {
        return array_merge(
            parent::getJsonRequestParamsList(),
            [
                'username',
                'password',
            ]
        );
    }
}
