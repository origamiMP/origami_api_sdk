<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSectionTranslationDtoNotConstructableException;

class FormSectionTranslationDto extends ApiResponseDto implements Arrayable
{
    public int $id;

    public int $languageId;

    public string $locale;

    public ?string $title;

    public ?string $description;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'          => 'id',
            'language_id' => 'languageId',
            'locale'      => 'locale',
            'title'       => 'title',
            'description' => 'description',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'          => ['required', 'integer'],
            'language_id' => ['required', 'integer'],
            'locale'      => ['required', 'string'],
            'title'       => ['present', 'nullable', 'string'],
            'description' => ['present', 'nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSectionTranslationDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
