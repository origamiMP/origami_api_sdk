<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationCheckPendingResponseDtoNotConstructableException;

class UserGroupInvitationCheckPendingResponseDto extends ApiResponseDto
{
    public string $email;
    public bool $hasPendingInvitation;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'email'                    => 'email',
            'has_pending_invitation'   => 'hasPendingInvitation',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'email'                    => ['required', 'string', 'email'],
            'has_pending_invitation'   => ['required', 'boolean'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationCheckPendingResponseDtoNotConstructableException($msg, previous: $previous);
    }
} 