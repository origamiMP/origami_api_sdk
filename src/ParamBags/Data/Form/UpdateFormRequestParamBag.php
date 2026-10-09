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
     * The Origami API replaces the whole tree with it: nodes sent without an id are created,
     * existing nodes left out are deleted. Each node sent is written as a whole: a property left
     * unset takes the API default (no condition, no params, no options).
     *
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
