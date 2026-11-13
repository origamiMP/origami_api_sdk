<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class ServiceResourceListDto extends ApiResponseDto
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
        return new ServiceResourceListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($resource) => new ServiceResourceDto($resource));
    }
}
