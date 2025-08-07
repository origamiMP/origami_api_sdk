<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class CreateSellerTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $description;

    public string $tos;

    protected function validationRulesForProperties(): array
    {
        return [
            'language_id' => ['required', 'integer'],
            'description' => ['string'],
            'tos'         => ['string'],
        ];
    }
}
