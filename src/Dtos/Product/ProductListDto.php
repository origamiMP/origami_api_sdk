<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Product;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class ProductListDto extends ApiResponseDto
{
    use HasIncludes, HasPagination;

    public static function getAvailableIncludes(): array
    {
        return ProductDto::getAvailableIncludes();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return $this->getPaginationAsDataStructureToProperties();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ProductListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($product) => new ProductDto($product));
    }
}
