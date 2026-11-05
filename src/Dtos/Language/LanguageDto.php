<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Language;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Language\LanguageDtoNotConstructableException;

class LanguageDto extends ApiResponseDto
{
    public int $id;

    public string $locale;

    public string $name;

    public bool $isDefault;

    public bool $isActive;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'         => 'id',
            'locale'     => 'locale',
            'name'       => 'name',
            'is_default' => 'isDefault',
            'is_active'  => 'isActive',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new LanguageDtoNotConstructableException($msg, previous: $previous);
    }
}
