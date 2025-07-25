<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationValidateResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class ValidateUserGroupInvitationRequestParamBag extends DataApiRequestParamBag
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

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationValidateResponseDto::class;
    }
}
