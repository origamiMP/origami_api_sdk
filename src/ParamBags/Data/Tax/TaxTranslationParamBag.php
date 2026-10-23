<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class TaxTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $name;

    protected function getApiKeysToProperties(): array
    {
        return [
            'language_id' => 'languageId',
            'name'        => 'name',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'language_id' => ['required', 'integer'],
            'name'        => ['required', 'string'],
        ];
    }
}
