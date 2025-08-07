<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class GetCurrentUserRequestParamBag extends RequestParamBag
{
    use HasIncludes;

    protected static function getRequestMainDto(): string
    {
        return UserDto::class;
    }
}
