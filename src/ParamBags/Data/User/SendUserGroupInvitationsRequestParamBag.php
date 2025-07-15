<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationsResponseDto;
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
            'emails'         => ['required', 'array', 'min:1'],
            'emails.*'       => ['required', 'string', 'email'],
            'onboardingUrl' => ['required', 'string', 'url'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationsResponseDto::class;
    }
}
