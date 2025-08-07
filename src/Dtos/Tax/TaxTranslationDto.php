<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Tax;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxTranslationDtoNotConstructableException;

class TaxTranslationDto extends ApiResponseDto
{
    public string $locale;

    public bool $default;

    public int $languageId;

    public string $name;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'locale'      => 'locale',
            'default'     => fn ($default) => $this->default = (bool) $default,
            'language_id' => 'languageId',
            'name'        => 'name',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'locale'      => ['required', 'string'],
            'default'     => ['required', 'boolean'],
            'language_id' => ['required', 'integer'],
            'name'        => ['present', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new TaxTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
