<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelResponseDtoNotConstructableException;

class UserGroupInvitationCancelDto extends ApiResponseDto
{
    public bool $success;

    public string $message;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'success' => 'success',
            'message' => 'message',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'success' => ['required', 'boolean'],
            'message' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationCancelResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
