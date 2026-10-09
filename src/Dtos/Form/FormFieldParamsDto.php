<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Forms\FormFieldParamsDtoWidthEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldParamsDtoNotConstructableException;

/**
 * Type-specific field params; the API sends `{}` when a field has none, so every param is optional.
 */
class FormFieldParamsDto extends ApiResponseDto implements Arrayable
{
    /** @var string[]|null */
    public ?array $extensions = null;

    public ?int $maxSizeKb = null;

    public ?int $maxFiles = null;

    public ?string $unit = null;

    public ?float $min = null;

    public ?float $max = null;

    public ?FormFieldParamsDtoWidthEnum $width = null;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'extensions'  => 'extensions',
            'max_size_kb' => 'maxSizeKb',
            'max_files'   => 'maxFiles',
            'unit'        => 'unit',
            'min'         => fn (?float $min) => $this->min = $min,
            'max'         => fn (?float $max) => $this->max = $max,
            'width'       => fn (?string $width) => $this->width = $width === null ? null : FormFieldParamsDtoWidthEnum::from($width),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'extensions'  => ['nullable', 'array'],
            'max_size_kb' => ['nullable', 'integer'],
            'max_files'   => ['nullable', 'integer'],
            'unit'        => ['nullable', 'string'],
            'min'         => ['nullable', 'numeric'],
            'max'         => ['nullable', 'numeric'],
            'width'       => ['nullable', Rule::in(collect(FormFieldParamsDtoWidthEnum::cases())->pluck('value'))],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormFieldParamsDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
