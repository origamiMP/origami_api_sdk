<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class CreateSellerTranslationParamBag extends ParamBag
{
    public int $languageId;

    public string $description;

    public string $tos;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'languageId'  => 'language_id',
            'description' => 'description',
            'tos'         => 'tos',
        ];
    }
}
