<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Guest\User;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class AcceptUserGroupInvitationRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public string $token;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'token',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'token' => ['required', 'string', 'size:64'],
        ];

        return [
            ...$rules,
            ...$this->getIncludeValidationRules(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationDto::class;
    }
}
