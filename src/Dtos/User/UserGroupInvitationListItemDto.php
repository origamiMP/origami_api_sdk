<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationListItemDtoNotConstructableException;

class UserGroupInvitationListItemDto extends ApiResponseDto
{
    public int $id;
    public string $email;
    public string $status;
    public Carbon $sentAt;
    public ?Carbon $acceptedAt;
    public ?Carbon $cancelledAt;
    public ?Carbon $expiredAt;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'email'        => 'email',
            'status'       => 'status',
            'sent_at'      => fn($date) => $this->sentAt = Carbon::parse($date),
            'accepted_at'  => fn($date) => $this->acceptedAt = is_null($date) ? null : Carbon::parse($date),
            'cancelled_at' => fn($date) => $this->cancelledAt = is_null($date) ? null : Carbon::parse($date),
            'expired_at'   => fn($date) => $this->expiredAt = is_null($date) ? null : Carbon::parse($date),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['required', 'integer'],
            'email'        => ['required', 'string', 'email'],
            'status'       => ['required', 'string'],
            'sent_at'      => ['required', 'date'],
            'accepted_at'  => ['present', 'nullable', 'date'],
            'cancelled_at' => ['present', 'nullable', 'date'],
            'expired_at'   => ['present', 'nullable', 'date'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationListItemDtoNotConstructableException($msg, previous: $previous);
    }
} 