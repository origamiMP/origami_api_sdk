<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormSectionParamBag extends ParamBag
{
    public int $id;

    /**
     * @var FormSectionTranslationParamBag[]
     */
    public array $translations;

    public FormConditionParamBag $condition;

    /**
     * @var FormFieldParamBag[]
     */
    public array $fields;

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['integer'],
            'translations' => ['required', 'array'],
            'fields'       => ['array'],
        ];
    }
}
