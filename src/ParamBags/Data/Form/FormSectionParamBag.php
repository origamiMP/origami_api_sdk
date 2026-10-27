<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormSectionParamBag extends ParamBag
{
    public int $id;

    /** @var array<int, FormSectionTranslationParamBag> */
    public array $translations;

    public FormConditionParamBag $condition;

    /** @var array<int, FormFieldParamBag> */
    public array $fields;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'id'           => 'id',
            'translations' => 'translations',
            'condition'    => 'condition',
            'fields'       => 'fields',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['integer'],
            'translations' => ['required', 'array'],
            'fields'       => ['array'],
        ];
    }
}
