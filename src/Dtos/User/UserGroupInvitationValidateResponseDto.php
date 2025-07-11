<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationValidateResponseDtoNotConstructableException;

class UserGroupInvitationValidateResponseDto extends ApiResponseDto
{
    public bool $success;

    public string $message;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

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
        return new UserGroupInvitationValidateResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
