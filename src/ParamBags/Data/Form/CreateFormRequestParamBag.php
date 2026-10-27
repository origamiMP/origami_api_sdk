<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class CreateFormRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

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
            'isActive'            => 'is_active',
            'isPurchaseEnabled'   => 'is_purchase_enabled',
            'isQuoteEnabled'      => 'is_quote_enabled',
            'isStandaloneEnabled' => 'is_standalone_enabled',
            'translations'        => 'translations',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'is_active'             => ['boolean'],
            'is_purchase_enabled'   => ['boolean'],
            'is_quote_enabled'      => ['boolean'],
            'is_standalone_enabled' => ['boolean'],
            'translations'          => ['required', 'array'],
        ];

        return [
            ...$rules,
            ...$this->getIncludeValidationRules(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return FormDto::class;
    }
}
