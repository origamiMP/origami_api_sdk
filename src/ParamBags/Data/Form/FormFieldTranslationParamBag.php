<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $label;

    public ?string $hint;

    public ?string $placeholder;

    protected function validationRulesForProperties(): array
    {
        return [
            'language_id' => ['required', 'integer'],
            'label'       => ['required', 'string', 'max:255'],
            'hint'        => ['nullable', 'string'],
            'placeholder' => ['nullable', 'string', 'max:255'],
        ];
    }
}
