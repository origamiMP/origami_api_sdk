<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationAcceptResponseDtoNotConstructableException;

class UserGroupInvitationAcceptResponseDto extends ApiResponseDto
{
    public int $id;

    public string $email;

    public string $status;

    public Carbon $acceptedAt;

    public string $onboardingUrl;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'             => 'id',
            'email'          => 'email',
            'status'         => 'status',
            'accepted_at'    => fn ($date) => $this->acceptedAt = Carbon::parse($date),
            'onboarding_url' => 'onboardingUrl',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'             => ['required', 'integer'],
            'email'          => ['required', 'string', 'email'],
            'status'         => ['required', 'string'],
            'accepted_at'    => ['required', 'date'],
            'onboarding_url' => ['required', 'string', 'url'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationAcceptResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
