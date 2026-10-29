<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormPageParamBag extends ParamBag
{
    public int $id;

    /** @var array<int, FormPageTranslationParamBag> */
    public array $translations;

    public FormConditionParamBag $condition;

    /** @var array<int, FormSectionParamBag> */
    public array $sections;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'id'           => 'id',
            'translations' => 'translations',
            'condition'    => 'condition',
            'sections'     => 'sections',
        ];
    }
}
