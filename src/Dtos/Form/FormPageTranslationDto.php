<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPageTranslationDtoNotConstructableException;

class FormPageTranslationDto extends ApiResponseDto
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

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormPageTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
