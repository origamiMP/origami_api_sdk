<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CreateFormRequestParamBag extends RequestParamBag
{
    public bool $isActive;

    public bool $isPurchaseEnabled;

    public bool $isQuoteEnabled;

    public bool $isStandaloneEnabled;

    /**
     * @var FormTranslationParamBag[]
     */
    public array $translations;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'isActive',
            'isPurchaseEnabled',
            'isQuoteEnabled',
            'isStandaloneEnabled',
            'translations',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'is_active'             => ['boolean'],
            'is_purchase_enabled'   => ['boolean'],
            'is_quote_enabled'      => ['boolean'],
            'is_standalone_enabled' => ['boolean'],
            'translations'          => ['required', 'array'],
        ];
    }
}
