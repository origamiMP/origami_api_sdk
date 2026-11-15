<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceSlotListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class ServiceSlotListDto extends ApiResponseDto
{
    use HasPagination;

    protected function getDefaultDataStructureToProperties(): array
    {
        return $this->getPaginationAsDataStructureToProperties();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSlotListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($slot) => new ServiceSlotDto($slot));
    }
}
