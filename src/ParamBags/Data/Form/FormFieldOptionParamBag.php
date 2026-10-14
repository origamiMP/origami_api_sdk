<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldOptionParamBag extends ParamBag
{
    public int $id;

    public string $value;

    /**
     * @var FormFieldOptionTranslationParamBag[]
     */
    public array $translations;

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['integer'],
            'value'        => ['required', 'string', 'max:64'],
            'translations' => ['required', 'array'],
        ];
    }
}
