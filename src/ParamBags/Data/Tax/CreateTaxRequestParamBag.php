<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Tax\TaxDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class CreateTaxRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public TaxDtoTypeEnum $type;

    public float $value;

    public bool $isDefault;

    /** @var array<int, TaxTranslationParamBag> */
    public array $translations;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'type'         => 'type',
            'value'        => 'value',
            'isDefault'    => 'is_default',
            'translations' => 'translations',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludesParamsList();
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'type'                       => ['required', Rule::enum(TaxDtoTypeEnum::class)],
            'value'                      => ['required', 'numeric'],
            'is_default'                 => ['boolean'],
            'translations'               => ['required', 'array'],
            'translations.*.language_id' => ['required', 'integer'],
            'translations.*.name'        => ['required', 'string'],
        ];

        return [
            ...$rules,
            ...$this->getIncludesValidationRules(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return TaxDto::class;
    }
}
