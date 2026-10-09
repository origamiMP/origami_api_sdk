<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Error;

enum OrigamiApiErrorCodeEnum: string
{
    case UNAUTHORIZED = '030101';
    case FORM_IN_USE = '140003';
    case FORM_DEFINITION_INVALID = '140011';
}
