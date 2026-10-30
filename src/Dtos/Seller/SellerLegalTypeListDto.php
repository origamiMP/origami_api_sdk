<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerLegalTypeListDtoNotConstructableException;

class SellerLegalTypeListDto extends ApiResponseDto
{
    /**
     * @var string[]
     */
    public array $types;

    public function __construct(array|object $apiResponse)
    {
        if (is_array($apiResponse)) {
            $apiResponse = (object)['types' => $apiResponse];
        }

        parent::__construct($apiResponse);
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'types' => 'types',
        ];
    }

    protected static function getDefaultNotConstructableException(string $msg, ?\Throwable $previous = null): ApiResponseDtoNotConstructableException
    {
        return new SellerLegalTypeListDtoNotConstructableException($msg, previous: $previous);
    }
}
