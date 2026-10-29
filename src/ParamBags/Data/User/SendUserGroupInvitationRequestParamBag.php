<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class SendUserGroupInvitationRequestParamBag extends RequestParamBag
{
    /**
     * Array of email addresses to send invitations to
     */
    public array $emails = [];

    /**
     * URL where the invited users will be redirected for onboarding
     */
    public string $onboardingUrl = '';

    protected function getJsonRequestParamsList(): array
    {
        return [
            'emails'        => 'emails',
            'onboardingUrl' => 'onboarding_url',
        ];
    }
}
