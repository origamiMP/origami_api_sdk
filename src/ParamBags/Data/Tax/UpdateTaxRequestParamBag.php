<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Taxes\TaxDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateTaxRequestParamBag extends RequestParamBag
{
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

    protected function validationRulesForProperties(): array
    {
        return [
            'type'                       => ['required', 'string'],
            'value'                      => ['required', 'numeric'],
            'is_default'                 => ['boolean'],
            'translations'               => ['required', 'array'],
            'translations.*.language_id' => ['required', 'integer'],
            'translations.*.name'        => ['required', 'string'],
        ];
    }
}
