<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationValidateResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class ValidateUserGroupInvitationRequestParamBag extends DataApiRequestParamBag
{
    public int $invitationId;
    public int $userGroupId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'invitation_id',
            'user_group_id',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'invitation_id' => ['required', 'integer'],
            'user_group_id' => ['required', 'integer'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationValidateResponseDto::class;
    }
} 