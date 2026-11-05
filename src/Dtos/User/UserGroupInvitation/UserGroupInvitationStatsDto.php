<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsDtoNotConstructableException;

class UserGroupInvitationStatsDto extends ApiResponseDto
{
    public int $total;

    public int $pending;

    public int $accepted;

    public int $cancelled;

    public int $expired;

    public float $acceptanceRate;

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

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationStatsDtoNotConstructableException($msg, previous: $previous);
    }
}
