<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormPageTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $title;

    public ?string $description;

    protected function validationRulesForProperties(): array
    {
        return [
            'language_id' => ['required', 'integer'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
