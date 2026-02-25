<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class GetUserGroupInvitationHistoryRequestParamBag extends RequestParamBag
{
    use HasIncludes;

    public string $email;

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getIncludeParamsList(),
            [
                'email',
            ],
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'email' => ['required', 'string', 'email'],
        ];

        return array_merge(
            $rules,
            $this->getIncludeValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationHistoryDto::class;
    }
}
