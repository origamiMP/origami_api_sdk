<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupInvitation\UserGroupInvitationResultDtoActionEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationResultDtoNotConstructableException;

class UserGroupInvitationResultDto extends ApiResponseDto
{
    public string $email;

    public bool $success;

    public ?int $invitationId;

    public ?UserGroupInvitationResultDtoActionEnum $action;

    public ?string $error;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'email'         => 'email',
            'success'       => 'success',
            'invitation_id' => 'invitationId',
            'action'        => fn ($action) => $this->action = UserGroupInvitationResultDtoActionEnum::from($action),
            'error'         => 'error',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationResultDtoNotConstructableException($msg, previous: $previous);
    }
}
