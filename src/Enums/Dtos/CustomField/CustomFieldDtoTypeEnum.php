<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField;

enum CustomFieldDtoTypeEnum: string
{
    case BOOLEAN = 'boolean';

    case FILE = 'file';

    case HTML = 'html';

    case IMAGE = 'image';

    case INPUT = 'input';

    case MULTI_SELECT = 'multiple';

    case SELECT = 'select';

    case TEXTAREA = 'textarea';
}
