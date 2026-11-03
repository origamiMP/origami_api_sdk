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

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new TaxTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
