<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $name;

    public ?string $description;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'languageId'  => 'language_id',
            'name'        => 'name',
            'description' => 'description',
        ];
    }
}
