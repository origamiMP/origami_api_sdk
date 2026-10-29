<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormSectionTranslationParamBag extends ParamBag
{
    public int $languageId;

    public ?string $title;

    public ?string $description;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'languageId'  => 'language_id',
            'title'       => 'title',
            'description' => 'description',
        ];
    }
}
