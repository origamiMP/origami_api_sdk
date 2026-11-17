<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalSummaryDtoNotConstructableException;

/**
 * The late withdrawals per provider, over the filters of the journal.
 */
class ServiceLateWithdrawalSummaryDto extends ApiResponseDto
{
    /** @var Collection<int, ServiceLateWithdrawalSellerSummaryDto> */
    public Collection $data;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn (array $data) => $this->initData($data),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceLateWithdrawalSummaryDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn (object $summary) => new ServiceLateWithdrawalSellerSummaryDto($summary));
    }
}
