<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopTaxDtoNotConstructableException;

class PrestashopTaxDto extends ApiResponseDto
{
    public int $prestashopId;

    public string $name;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'prestashop_id' => 'prestashopId',
            'name'          => 'name',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new PrestashopTaxDtoNotConstructableException($msg, previous: $previous);
    }
}
