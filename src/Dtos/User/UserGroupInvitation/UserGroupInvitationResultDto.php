<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation;

use Illuminate\Validation\Rule;
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

    protected function validationRulesForProperties(): array
    {
        $actions = collect(UserGroupInvitationResultDtoActionEnum::cases())->pluck('value');

        return [
            'email'         => ['required', 'string', 'email'],
            'success'       => ['required', 'boolean'],
            'invitation_id' => ['present', 'nullable', 'integer'],
            'action'        => ['present', 'nullable', Rule::in($actions)],
            'error'         => ['present', 'nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationResultDtoNotConstructableException($msg, previous: $previous);
    }
}
