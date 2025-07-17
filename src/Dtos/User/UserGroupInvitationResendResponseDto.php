<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationResendResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class UserGroupInvitationResendResponseDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    public ?int $userGroupId;

    public string $email;

    public string $status;

    public string $onboardingUrl;

    public ?Carbon $sentAt;

    public ?Carbon $acceptedAt;

    public Carbon $expiresAt;

    public bool $isExpired;

    public bool $isValid;

    public string $token;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'             => 'id',
            'user_group_id'  => 'userGroupId',
            'email'          => 'email',
            'status'         => 'status',
            'onboarding_url' => 'onboardingUrl',
            'sent_at'        => fn ($date) => $this->sentAt = is_null($date) ? null : Carbon::parse($date),
            'accepted_at'    => fn ($date) => $this->acceptedAt = is_null($date) ? null : Carbon::parse($date),
            'expires_at'     => fn ($date) => $this->expiresAt = Carbon::parse($date),
            'is_expired'     => 'isExpired',
            'is_valid'       => 'isValid',
            'token'          => 'token',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'             => ['required', 'integer'],
            'user_group_id'  => ['present', 'nullable', 'integer'],
            'email'          => ['required', 'string', 'email'],
            'status'         => ['required', 'string'],
            'onboarding_url' => ['required', 'string', 'url'],
            'sent_at'        => ['present', 'nullable', 'date'],
            'accepted_at'    => ['present', 'nullable', 'date'],
            'expires_at'     => ['required', 'date'],
            'is_expired'     => ['required', 'boolean'],
            'is_valid'       => ['required', 'boolean'],
            'token'          => ['required', 'string'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationResendResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
