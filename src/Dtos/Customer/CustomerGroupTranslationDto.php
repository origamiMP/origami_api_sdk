<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Customer;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerGroupTranslationDtoNotConstructableException;

class CustomerGroupTranslationDto extends ApiResponseDto
{
    public int $id;

    public string $name;

    public int $languageId;

    public string $locale;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'          => 'id',
            'name'        => 'name',
            'language_id' => 'languageId',
            'locale'      => 'locale',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomerGroupTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
