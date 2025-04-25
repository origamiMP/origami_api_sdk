<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class GetCurrentUserRequestParamBag extends DataApiRequestParamBag
{
    protected static function getRequestMainDto(): string
    {
        return UserDto::class;
    }
}
