<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopOrderStateDtoNotConstructableException;

class PrestashopOrderStateDto extends ApiResponseDto
{
    public int $prestashopId;

    public string $name;

    public ?string $color;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'prestashop_id' => 'prestashopId',
            'name'          => 'name',
            'color'         => 'color',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new PrestashopOrderStateDtoNotConstructableException($msg, previous: $previous);
    }
}
