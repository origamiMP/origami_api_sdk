<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Guest\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class AcceptUserGroupInvitationRequestParamBag extends RequestParamBag
{
    public string $token;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'token',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'token' => ['required', 'string', 'size:64'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationDto::class;
    }
}
