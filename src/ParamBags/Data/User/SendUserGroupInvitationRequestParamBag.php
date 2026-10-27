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

    protected function validationRulesForProperties(): array
    {
        return [
            'emails'         => ['required', 'array', 'min:1', 'max:10'],
            'emails.*'       => ['required', 'string', 'email', 'max:255'],
            'onboarding_url' => ['required', 'string', 'url', 'max:500'],
        ];
    }
}
