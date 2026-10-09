<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateFormRequestParamBag extends RequestParamBag
{
    public bool $isActive;

    public bool $isPurchaseEnabled;

    public bool $isQuoteEnabled;

    public bool $isStandaloneEnabled;

    /**
     * @var FormTranslationParamBag[]
     */
    public array $translations;

    /**
     * @var FormPageParamBag[]
     */
    public array $pages;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'isActive',
            'isPurchaseEnabled',
            'isQuoteEnabled',
            'isStandaloneEnabled',
            'translations',
            'pages',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'is_active'             => ['boolean'],
            'is_purchase_enabled'   => ['boolean'],
            'is_quote_enabled'      => ['boolean'],
            'is_standalone_enabled' => ['boolean'],
            'translations'          => ['array'],
            'pages'                 => ['array'],
        ];
    }
}
