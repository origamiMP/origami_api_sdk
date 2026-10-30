<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateDtoNotConstructableException;

class UserGroupInvitationValidateDto extends ApiResponseDto
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

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationValidateDtoNotConstructableException($msg, previous: $previous);
    }
}
