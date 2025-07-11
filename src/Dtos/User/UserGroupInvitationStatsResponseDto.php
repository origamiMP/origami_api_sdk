<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationStatsResponseDtoNotConstructableException;

class UserGroupInvitationStatsResponseDto extends ApiResponseDto
{
    public int $totalInvitations;

    public int $pendingInvitations;

    public int $acceptedInvitations;

    public int $cancelledInvitations;

    public int $expiredInvitations;

    public float $acceptanceRate;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'total_invitations'     => 'totalInvitations',
            'pending_invitations'   => 'pendingInvitations',
            'accepted_invitations'  => 'acceptedInvitations',
            'cancelled_invitations' => 'cancelledInvitations',
            'expired_invitations'   => 'expiredInvitations',
            'acceptance_rate'       => 'acceptanceRate',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'total_invitations'     => ['required', 'integer', 'min:0'],
            'pending_invitations'   => ['required', 'integer', 'min:0'],
            'accepted_invitations'  => ['required', 'integer', 'min:0'],
            'cancelled_invitations' => ['required', 'integer', 'min:0'],
            'expired_invitations'   => ['required', 'integer', 'min:0'],
            'acceptance_rate'       => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationStatsResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
