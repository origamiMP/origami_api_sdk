<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationResultDtoNotConstructableException;

class UserGroupInvitationResultDto extends ApiResponseDto
{
    public string $email;

    public bool $success;

    public ?int $invitationId;

    public ?string $action;

    public ?string $error;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'email'         => 'email',
            'success'       => 'success',
            'invitation_id' => 'invitationId',
            'action'        => 'action',
            'error'         => 'error',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'email'         => ['required', 'string', 'email'],
            'success'       => ['required', 'boolean'],
            'invitation_id' => ['present', 'nullable', 'integer'],
            'action'        => ['present', 'nullable', 'string'],
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
