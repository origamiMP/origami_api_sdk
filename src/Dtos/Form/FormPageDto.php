<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPageDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class FormPageDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public int $position;

    public ?FormConditionDto $condition;

    /** @var Collection<int, FormPageTranslationDto> */
    public Collection $translations;

    /** @var Collection<int, FormSectionDto> */
    public Collection $sections;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => FormPageTranslationDto::class,
            'sections'     => FormSectionDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'position'     => 'position',
            'condition'    => fn (?object $condition) => $this->condition = $condition ? new FormConditionDto($condition) : null,
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, FormPageTranslationDto::class),
            'sections'     => fn (object $sections) => $this->sections = $this->collectIncluded($sections, FormSectionDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormPageDtoNotConstructableException($msg, previous: $previous);
    }
}
