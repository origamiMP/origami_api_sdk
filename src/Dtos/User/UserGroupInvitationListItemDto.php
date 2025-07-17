<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationListItemDtoNotConstructableException;

class UserGroupInvitationListItemDto extends ApiResponseDto
{
    public int $id;

    public ?int $userGroupId;

    public string $status;

    public string $email;

    public string $onboardingUrl;

    public Carbon $sentAt;

    public ?Carbon $acceptedAt;

    public Carbon $expiresAt;

    public bool $isExpired;

    public bool $isValid;

    public string $token;

    public Carbon $createdAt;

    public Carbon $updatedAt;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'             => 'id',
            'user_group_id'  => 'userGroupId',
            'status'         => 'status',
            'email'          => 'email',
            'onboarding_url' => 'onboardingUrl',
            'sent_at'        => fn ($date) => $this->sentAt = Carbon::parse($date),
            'accepted_at'    => fn ($date) => $this->acceptedAt = is_null($date) ? null : Carbon::parse($date),
            'expires_at'     => fn ($date) => $this->expiresAt = Carbon::parse($date),
            'is_expired'     => 'isExpired',
            'is_valid'       => 'isValid',
            'token'          => 'token',
            'created_at'     => fn ($date) => $this->createdAt = Carbon::parse($date),
            'updated_at'     => fn ($date) => $this->updatedAt = Carbon::parse($date),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'             => ['required', 'integer'],
            'user_group_id'  => ['present', 'nullable', 'integer'],
            'status'         => ['required', 'string'],
            'email'          => ['required', 'string', 'email'],
            'onboarding_url' => ['required', 'string', 'url'],
            'sent_at'        => ['required', 'date'],
            'accepted_at'    => ['present', 'nullable', 'date'],
            'expires_at'     => ['required', 'date'],
            'is_expired'     => ['required', 'boolean'],
            'is_valid'       => ['required', 'boolean'],
            'token'          => ['required', 'string'],
            'created_at'     => ['required', 'date'],
            'updated_at'     => ['required', 'date'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationListItemDtoNotConstructableException($msg, previous: $previous);
    }
}
