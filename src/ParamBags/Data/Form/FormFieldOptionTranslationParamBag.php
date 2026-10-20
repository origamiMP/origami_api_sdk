<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldOptionTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $label;

    protected function getApiKeysToProperties(): array
    {
        return [
            'language_id' => 'languageId',
            'label'       => 'label',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'language_id' => ['required', 'integer'],
            'label'       => ['required', 'string', 'max:255'],
        ];
    }
}
