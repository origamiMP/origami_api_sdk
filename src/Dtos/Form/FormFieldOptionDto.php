<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldOptionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class FormFieldOptionDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public string $value;

    public int $position;

    /** @var Collection<int, FormFieldOptionTranslationDto> */
    public Collection $translations;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => FormFieldOptionTranslationDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'value'        => 'value',
            'position'     => 'position',
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, FormFieldOptionTranslationDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['required', 'integer'],
            'value'        => ['required', 'string'],
            'position'     => ['required', 'integer'],
            'translations' => ['required', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormFieldOptionDtoNotConstructableException($msg, previous: $previous);
    }
}
