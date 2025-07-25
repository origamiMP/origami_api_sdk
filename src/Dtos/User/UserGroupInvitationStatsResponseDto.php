<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationStatsResponseDtoNotConstructableException;

class UserGroupInvitationStatsResponseDto extends ApiResponseDto
{
    public int $total;

    public int $pending;

    public int $accepted;

    public int $cancelled;

    public int $expired;

    public float $acceptanceRate;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'total'           => 'total',
            'pending'         => 'pending',
            'accepted'        => 'accepted',
            'cancelled'       => 'cancelled',
            'expired'         => 'expired',
            'acceptance_rate' => 'acceptanceRate',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'total'           => ['required', 'integer', 'min:0'],
            'pending'         => ['required', 'integer', 'min:0'],
            'accepted'        => ['required', 'integer', 'min:0'],
            'cancelled'       => ['required', 'integer', 'min:0'],
            'expired'         => ['required', 'integer', 'min:0'],
            'acceptance_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationStatsResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
