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
            'token'         => 'token',
            'user_group_id' => 'userGroupId',
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
