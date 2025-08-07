<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class ValidateUserGroupInvitationRequestParamBag extends RequestParamBag
{
    public string $token;

    public int $userGroupId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'token',
            'userGroupId',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'token'       => ['required', 'string'],
            'userGroupId' => ['required', 'integer'],
        ];
    }
}
