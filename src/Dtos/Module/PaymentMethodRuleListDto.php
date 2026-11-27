<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\PaymentMethodRuleListDtoNotConstructableException;

class PaymentMethodRuleListDto extends ApiResponseDto
{
    /** @var Collection<int, PaymentMethodRuleDto> */
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
        return new PaymentMethodRuleListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($paymentMethodRule) => new PaymentMethodRuleDto($paymentMethodRule));
    }
}
