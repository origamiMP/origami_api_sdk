<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldOptionTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $label;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'languageId' => 'language_id',
            'label'      => 'label',
        ];
    }
}
