<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Shipping;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Shipping\ShippingZoneTranslationDtoNotConstructableException;

class ShippingZoneTranslationDto extends ApiResponseDto
{
    public string $locale;

    public bool $default;

    public int $languageId;

    public string $name;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'locale'      => 'locale',
            'default'     => 'default',
            'language_id' => 'languageId',
            'name'        => 'name',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ShippingZoneTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
