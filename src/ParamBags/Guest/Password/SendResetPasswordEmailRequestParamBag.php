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
            'email'     => 'email',
            'resetLink' => 'reset_link',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'email'      => ['required', 'string', 'email'],
            'reset_link' => ['string', 'url'],
        ];
    }
}
