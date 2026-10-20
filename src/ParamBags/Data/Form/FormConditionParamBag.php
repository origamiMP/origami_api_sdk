<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormConditionDtoOperatorEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormConditionParamBag extends ParamBag
{
    public string $fieldKey;

    public FormConditionDtoOperatorEnum $operator;

    public ?string $value;

    protected function getApiKeysToProperties(): array
    {
        return [
            'field_key' => 'fieldKey',
            'operator'  => 'operator',
            'value'     => 'value',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'field_key' => ['required', 'string'],
            'operator'  => ['required', 'string'],
            'value'     => ['nullable', 'string', 'max:255'],
        ];
    }
}
