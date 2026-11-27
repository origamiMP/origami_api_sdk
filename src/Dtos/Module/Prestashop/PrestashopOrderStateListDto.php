<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopOrderStateListDtoNotConstructableException;

class PrestashopOrderStateListDto extends ApiResponseDto
{
    /** @var Collection<int, PrestashopOrderStateDto> */
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
        return new PrestashopOrderStateListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($orderState) => new PrestashopOrderStateDto($orderState));
    }
}
