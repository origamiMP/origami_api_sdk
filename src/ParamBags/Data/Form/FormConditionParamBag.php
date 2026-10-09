<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormConditionDtoOperatorEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormConditionParamBag extends ParamBag
{
    public string $fieldKey;

    public FormConditionDtoOperatorEnum $operator;

    public string $value;

    protected function validationRulesForProperties(): array
    {
        return [
            'field_key' => ['required', 'string'],
            'operator'  => ['required', 'string'],
            'value'     => ['required', 'string', 'max:255'],
        ];
    }
}
