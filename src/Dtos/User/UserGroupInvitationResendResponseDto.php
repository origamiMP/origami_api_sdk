<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationResendResponseDtoNotConstructableException;

class UserGroupInvitationResendResponseDto extends ApiResponseDto
{
    public int $id;

    public string $email;

    public string $status;

    public string $token;

    public Carbon $tokenExpiresAt;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'               => 'id',
            'email'            => 'email',
            'status'           => 'status',
            'token'            => 'token',
            'token_expires_at' => fn ($date) => $this->tokenExpiresAt = Carbon::parse($date),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'               => ['required', 'integer'],
            'email'            => ['required', 'string', 'email'],
            'status'           => ['required', 'string'],
            'token'            => ['required', 'string'],
            'token_expires_at' => ['required', 'date'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationResendResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
