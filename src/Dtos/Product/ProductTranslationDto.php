<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Product;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductTranslationDtoNotConstructableException;

class ProductTranslationDto extends ApiResponseDto
{
    public string $locale;

    public int $languageId;

    public bool $default;

    public ?string $name;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'locale'      => 'locale',
            'language_id' => 'languageId',
            'default'     => 'default',
            'name'        => 'name',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ProductTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
