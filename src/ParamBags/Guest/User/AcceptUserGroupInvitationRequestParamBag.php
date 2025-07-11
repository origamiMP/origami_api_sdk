<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Guest\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationAcceptResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class AcceptUserGroupInvitationRequestParamBag extends RequestParamBag
{
    public string $token = '';

    protected function getJsonRequestParamsList(): array
    {
        return [
            'token',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'token' => ['required', 'string'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationAcceptResponseDto::class;
    }
} 