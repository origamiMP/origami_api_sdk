<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Tax\TaxDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateTaxRequestParamBag extends RequestParamBag
{
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
}
