<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldParamsDtoWidthEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldParamsParamBag extends ParamBag
{
    /**
     * @var string[]
     */
    public array $extensions;

    public int $maxSizeKb;

    public int $maxFiles;

    public string $unit;

    public int|float $min;

    public int|float $max;

    public FormFieldParamsDtoWidthEnum $width;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'extensions' => 'extensions',
            'maxSizeKb'  => 'max_size_kb',
            'maxFiles'   => 'max_files',
            'unit'       => 'unit',
            'min'        => 'min',
            'max'        => 'max',
            'width'      => 'width',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'extensions'  => ['array'],
            'max_size_kb' => ['integer', 'min:1'],
            'max_files'   => ['integer', 'min:1'],
            'unit'        => ['string', 'max:8'],
            'min'         => ['numeric'],
            'max'         => ['numeric'],
            'width'       => [Rule::enum(FormFieldParamsDtoWidthEnum::class)],
        ];
    }
}
