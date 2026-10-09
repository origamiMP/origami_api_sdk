<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form;

enum FormFieldDtoTypeEnum: string
{
    case INPUT = 'input';

    case TEXTAREA = 'textarea';

    case NUMBER = 'number';

    case SELECT = 'select';

    case MULTIPLE = 'multiple';

    case CHECKBOX = 'checkbox';

    case RADIO = 'radio';

    case DATE = 'date';

    case BOOLEAN = 'boolean';

    case FILE = 'file';
}
