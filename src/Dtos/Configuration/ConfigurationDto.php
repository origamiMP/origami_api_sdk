<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Configuration;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Configuration\ConfigurationDtoNotConstructableException;

class ConfigurationDto extends ApiResponseDto
{
    public string $key;

    /**
     * Its type depends on the configuration: scalar, list, object or null.
     */
    public mixed $value;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'key'   => 'key',
            'value' => 'value',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'key'   => ['required', 'string'],
            'value' => ['present'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ConfigurationDtoNotConstructableException($msg, previous: $previous);
    }
}
