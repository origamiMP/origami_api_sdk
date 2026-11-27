<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Payment;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment\PaymentPspCardDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Payment\PaymentPspCardDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * A card a user group saved at a payment provider, to be charged later. Its brand and last digits are null when the
 * provider did not give them.
 */
class PaymentPspCardDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public ?string $name;

    public int $userGroupId;

    public int $paymentPspId;

    public PaymentPspCardDtoStateEnum $state;

    public ?string $brand;

    public ?string $last4;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'psp',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'             => 'id',
            'name'           => 'name',
            'user_group_id'  => 'userGroupId',
            'payment_psp_id' => 'paymentPspId',
            'state'          => fn ($state) => $this->state = PaymentPspCardDtoStateEnum::from($state),
            'brand'          => 'brand',
            'last4'          => 'last4',
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new PaymentPspCardDtoNotConstructableException($msg, previous: $previous);
    }
}
