<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSectionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class FormSectionDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public int $position;

    public ?FormConditionDto $condition;

    /** @var Collection<int, FormSectionTranslationDto> */
    public Collection $translations;

    /** @var Collection<int, FormFieldDto> */
    public Collection $fields;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => FormSectionTranslationDto::class,
            'fields'       => FormFieldDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'position'     => 'position',
            'condition'    => fn (?object $condition) => $this->condition = $condition ? new FormConditionDto($condition) : null,
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, FormSectionTranslationDto::class),
            'fields'       => fn (object $fields) => $this->fields = $this->collectIncluded($fields, FormFieldDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['required', 'integer'],
            'position'     => ['required', 'integer'],
            'condition'    => ['present'],
            'translations' => ['required', 'array'],
            'fields'       => ['required', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSectionDtoNotConstructableException($msg, previous: $previous);
    }
}
