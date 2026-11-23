<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceDtoNotConstructableException;

class ServiceResourceDto extends ApiResponseDto
{
    public int $id;

    public int $serviceSiteId;

    public string $type;

    public string $name;

    public bool $isActive;

    public int $position;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'              => 'id',
            'service_site_id' => 'serviceSiteId',
            'type'            => 'type',
            'name'            => 'name',
            'is_active'       => 'isActive',
            'position'        => 'position',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceResourceDtoNotConstructableException($msg, previous: $previous);
    }
}
