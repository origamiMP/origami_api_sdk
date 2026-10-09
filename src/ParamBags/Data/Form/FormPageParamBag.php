<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormPageParamBag extends ParamBag
{
    public int $id;

    /**
     * @var FormPageTranslationParamBag[]
     */
    public array $translations;

    public FormConditionParamBag $condition;

    /**
     * @var FormSectionParamBag[]
     */
    public array $sections;

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['integer'],
            'translations' => ['required', 'array'],
            'sections'     => ['required', 'array'],
        ];
    }
}
