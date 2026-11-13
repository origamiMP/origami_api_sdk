<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class ServiceAvailabilityReviewListDto extends ApiResponseDto
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
        return new ServiceAvailabilityReviewListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($review) => new ServiceAvailabilityReviewDto($review));
    }
}
