<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldOptionParamBag extends ParamBag
{
    public int $id;

    public string $value;

    /** @var array<int, FormFieldOptionTranslationParamBag> */
    public array $translations;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'id'           => 'id',
            'value'        => 'value',
            'translations' => 'translations',
        ];
    }
}
