<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class ServiceLateWithdrawalListDto extends ApiResponseDto
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
        return new ServiceLateWithdrawalListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($withdrawal) => new ServiceLateWithdrawalDto($withdrawal));
    }
}
