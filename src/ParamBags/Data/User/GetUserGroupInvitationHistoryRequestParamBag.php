<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class GetUserGroupInvitationHistoryRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public string $email;

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getIncludesParamsList(),
            'email' => 'email',
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationHistoryDto::class;
    }
}
