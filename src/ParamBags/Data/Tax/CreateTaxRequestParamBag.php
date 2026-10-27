<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Taxes\TaxDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class CreateTaxRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public TaxDtoTypeEnum $type;

    public float $value;

    public bool $isDefault;

    /**
     * @var TaxTranslationParamBag[]
     */
    public array $translations;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'type',
            'value',
            'isDefault',
            'translations',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'type'                       => ['required', 'string'],
            'value'                      => ['required', 'numeric'],
            'is_default'                 => ['boolean'],
            'translations'               => ['required', 'array'],
            'translations.*.language_id' => ['required', 'integer'],
            'translations.*.name'        => ['required', 'string'],
        ];

        return [
            ...$rules,
            ...$this->getIncludeValidationRules(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return TaxDto::class;
    }
}
