<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationHistoryResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class GetUserGroupInvitationHistoryRequestParamBag extends DataApiRequestParamBag
{
    public string $email;

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            [
                'email',
            ],
        );
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationHistoryResponseDto::class;
    }
}
