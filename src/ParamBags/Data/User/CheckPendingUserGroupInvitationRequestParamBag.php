<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CheckPendingUserGroupInvitationRequestParamBag extends RequestParamBag
{
    public string $email;

    protected function getQueryRequestParamsList(): array
    {
        return [
            'email' => 'email',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
        ];
    }
}
