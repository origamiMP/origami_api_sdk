<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationCheckPendingResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class CheckPendingUserGroupInvitationRequestParamBag extends DataApiRequestParamBag
{
    public string $email = '';

    protected function getQueryRequestParamsList(): array
    {
        return [
            'email',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationCheckPendingResponseDto::class;
    }
} 