<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldOptionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludedCollections;

class FormFieldOptionDto extends ApiResponseDto implements Arrayable
{
    use HasIncludedCollections;

    public int $id;

    public string $value;

    public int $position;

    /** @var Collection<int, FormFieldOptionTranslationDto> */
    public Collection $translations;

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
            'translations' => ['required'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormFieldOptionDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
