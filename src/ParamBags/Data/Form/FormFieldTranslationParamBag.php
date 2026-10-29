<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $label;

    public ?string $hint;

    public ?string $placeholder;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'languageId'  => 'language_id',
            'label'       => 'label',
            'hint'        => 'hint',
            'placeholder' => 'placeholder',
        ];
    }
}
