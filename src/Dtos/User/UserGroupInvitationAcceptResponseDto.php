<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationAcceptResponseDtoNotConstructableException;

class UserGroupInvitationAcceptResponseDto extends ApiResponseDto
{
    /**
     * @var array
     */
    public array $data;

    public string $message;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data'    => fn ($data) => $this->data = $data,
            'message' => 'message',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data'    => ['required', 'array'],
            'data.*.email' => ['required', 'string', 'email'],
            'data.*.success' => ['required', 'boolean'],
            'data.*.invitation_id' => ['required', 'integer'],
            'data.*.action' => ['required', 'string'],
            'message' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationAcceptResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
