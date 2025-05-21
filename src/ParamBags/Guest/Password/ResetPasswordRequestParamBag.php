<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class ResetPasswordRequestParamBag extends RequestParamBag
{
    /**
     * Email address of the user requesting password reset
     */
    public string $email;

    /**
     * The new password for the user.
     */
    public string $password;

    /**
     * The new password confirmation.
     */
    public string $passwordConfirmation;

    /**
     * The token for the password reset.
     */
    public string $token;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'email',
            'password',
            'passwordConfirmation',
            'token',
        ];
    }
}
