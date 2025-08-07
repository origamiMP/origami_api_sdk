<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationSendDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class SendUserGroupInvitationsRequestParamBag extends DataApiRequestParamBag
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
            'emails',
            'onboardingUrl',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'emails'        => ['required', 'array', 'min:1', 'max:10'],
            'emails.*'      => ['required', 'string', 'email', 'max:255'],
            'onboardingUrl' => ['required', 'string', 'url', 'max:500'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationSendDto::class;
    }
}
