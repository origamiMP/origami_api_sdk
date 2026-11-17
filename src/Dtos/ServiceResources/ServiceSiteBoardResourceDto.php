<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardResourceDtoNotConstructableException;

class ServiceSiteBoardResourceDto extends ApiResponseDto
{
    public int $id;

    public string $type;

    public string $name;

    public bool $isActive;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'        => 'id',
            'type'      => 'type',
            'name'      => 'name',
            'is_active' => 'isActive',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteBoardResourceDtoNotConstructableException($msg, previous: $previous);
    }
}
