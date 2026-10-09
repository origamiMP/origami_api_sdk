<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPageDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludedCollections;

class FormPageDto extends ApiResponseDto implements Arrayable
{
    use HasIncludedCollections;

    public int $id;

    public int $position;

    public ?FormConditionDto $condition;

    /** @var Collection<int, FormPageTranslationDto> */
    public Collection $translations;

    /** @var Collection<int, FormSectionDto> */
    public Collection $sections;

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

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['required', 'integer'],
            'position'     => ['required', 'integer'],
            'condition'    => ['present'],
            'translations' => ['required'],
            'sections'     => ['required'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormPageDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
