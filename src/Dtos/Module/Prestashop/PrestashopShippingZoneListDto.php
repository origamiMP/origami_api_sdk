<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopShippingZoneListDtoNotConstructableException;

class PrestashopShippingZoneListDto extends ApiResponseDto
{
    /** @var Collection<int, PrestashopShippingZoneDto> */
    public Collection $data;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new PrestashopShippingZoneListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($shippingZone) => new PrestashopShippingZoneDto($shippingZone));
    }
}
