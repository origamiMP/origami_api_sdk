<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalSellerSummaryDtoNotConstructableException;

class ServiceLateWithdrawalSellerSummaryDto extends ApiResponseDto
{
    public int $sellerId;

    public int $withdrawals;

    public int $minutes;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'seller_id'   => 'sellerId',
            'withdrawals' => 'withdrawals',
            'minutes'     => 'minutes',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceLateWithdrawalSellerSummaryDtoNotConstructableException($msg, previous: $previous);
    }
}
