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
            'is_active'             => 'isActive',
            'is_purchase_enabled'   => 'isPurchaseEnabled',
            'is_quote_enabled'      => 'isQuoteEnabled',
            'is_standalone_enabled' => 'isStandaloneEnabled',
            'translations'          => 'translations',
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

        return array_merge(
            $rules,
            $this->getIncludeValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return FormDto::class;
    }
}
