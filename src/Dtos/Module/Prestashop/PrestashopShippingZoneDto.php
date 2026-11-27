<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopShippingZoneDtoNotConstructableException;

class PrestashopShippingZoneDto extends ApiResponseDto
{
    public int $prestashopId;

    public string $name;

    public bool $active;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'prestashop_id' => 'prestashopId',
            'name'          => 'name',
            'active'        => 'active',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new PrestashopShippingZoneDtoNotConstructableException($msg, previous: $previous);
    }
}
