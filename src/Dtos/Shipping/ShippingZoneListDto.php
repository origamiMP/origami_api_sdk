<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Shipping;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Shipping\ShippingZoneListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class ShippingZoneListDto extends ApiResponseDto
{
    use HasIncludes, HasPagination;

    public static function getAvailableIncludes(): array
    {
        return ShippingZoneDto::getAvailableIncludes();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return $this->getPaginationAsDataStructureToProperties();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ShippingZoneListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($shippingZone) => new ShippingZoneDto($shippingZone));
    }
}
