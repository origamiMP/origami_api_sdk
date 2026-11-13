<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardSiteDtoNotConstructableException;

class ServiceSiteBoardSiteDto extends ApiResponseDto
{
    public int $id;

    public string $name;

    public string $timezone;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'       => 'id',
            'name'     => 'name',
            'timezone' => 'timezone',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteBoardSiteDtoNotConstructableException($msg, previous: $previous);
    }
}
