<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldTranslationDtoNotConstructableException;

class FormFieldTranslationDto extends ApiResponseDto implements Arrayable
{
    public int $id;

    public int $languageId;

    public string $locale;

    public ?string $label;

    public ?string $hint;

    public ?string $placeholder;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'          => 'id',
            'language_id' => 'languageId',
            'locale'      => 'locale',
            'label'       => 'label',
            'hint'        => 'hint',
            'placeholder' => 'placeholder',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'          => ['required', 'integer'],
            'language_id' => ['required', 'integer'],
            'locale'      => ['required', 'string'],
            'label'       => ['present', 'nullable', 'string'],
            'hint'        => ['present', 'nullable', 'string'],
            'placeholder' => ['present', 'nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormFieldTranslationDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
