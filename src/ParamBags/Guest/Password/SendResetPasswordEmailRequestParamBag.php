<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class SendResetPasswordEmailRequestParamBag extends RequestParamBag
{
    /**
     * Email address of the user requesting password reset
     */
    public string $email;

    /**
     * If supplied, customize the reset link that will be contained in the email.
     */
    public string $resetLink;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'email',
            'resetLink',
        ];
    }
}
