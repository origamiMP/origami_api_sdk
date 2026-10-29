<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax;

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

    protected static function getRequestMainDto(): string
    {
        return TaxDto::class;
    }
}
