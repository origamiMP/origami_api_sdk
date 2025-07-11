<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationHistoryItemDtoNotConstructableException;

class UserGroupInvitationHistoryItemDto extends ApiResponseDto
{
    public int $id;

    public string $status;

    public Carbon $sentAt;

    public ?Carbon $acceptedAt;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'          => 'id',
            'status'      => 'status',
            'sent_at'     => fn ($date) => $this->sentAt = Carbon::parse($date),
            'accepted_at' => fn ($date) => $this->acceptedAt = is_null($date) ? null : Carbon::parse($date),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'          => ['required', 'integer'],
            'status'      => ['required', 'string'],
            'sent_at'     => ['required', 'date'],
            'accepted_at' => ['present', 'nullable', 'date'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationHistoryItemDtoNotConstructableException($msg, previous: $previous);
    }
}
