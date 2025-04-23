<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\User;

enum UserDtoStateEnum: string
{
    case BANNED = 'banned';

    case ENABLED = 'enabled';
}
