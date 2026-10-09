<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form;

enum FormConditionDtoOperatorEnum: string
{
    case EQUALS = 'equals';

    case NOT_EQUALS = 'not_equals';

    case CONTAINS = 'contains';
}
