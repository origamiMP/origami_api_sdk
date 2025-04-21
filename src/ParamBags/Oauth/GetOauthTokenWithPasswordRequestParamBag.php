<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\GetOauthTokenGrantTypeParamEnum;

class GetOauthTokenWithPasswordRequestParamBag extends GetOauthTokenRequestParamBag
{
    public string $username;

    public string $password;

    /**
     * @param  string  $username Username of the user you want to log in
     * @param  string  $password Password of the user you want to log in
     */
    public function __construct(
        string $username,
        string $password,
    ) {
        parent::__construct(GetOauthTokenGrantTypeParamEnum::PASSWORD);

        $this->username = $username;
        $this->password = $password;
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
