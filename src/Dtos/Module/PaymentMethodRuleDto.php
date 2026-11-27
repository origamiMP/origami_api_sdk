<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\PaymentMethodRuleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class PaymentMethodRuleDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    public int $moduleId;

    public string $paymentMethod;

    public ?string $minAmount;

    public ?string $maxAmount;

    /** @var array<int, int> */
    public array $customerGroupIds;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                 => 'id',
            'module_id'          => 'moduleId',
            'payment_method'     => 'paymentMethod',
            'min_amount'         => 'minAmount',
            'max_amount'         => 'maxAmount',
            'customer_group_ids' => 'customerGroupIds',
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new PaymentMethodRuleDtoNotConstructableException($msg, previous: $previous);
    }
}
