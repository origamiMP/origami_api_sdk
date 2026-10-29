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
            'token'       => 'token',
            'userGroupId' => 'user_group_id',
        ];
    }
}
