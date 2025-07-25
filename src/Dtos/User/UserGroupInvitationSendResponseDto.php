<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationsSendResponseDtoNotConstructableException;

class UserGroupInvitationSendResponseDto extends ApiResponseDto
{
    /**
     * @var Collection|UserGroupInvitationResultDto[]
     */
    public Collection $data;

    public string $message;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data'    => fn ($data) => $this->initData($data),
            'message' => 'message',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data'    => ['required', 'array'],
            'message' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationsSendResponseDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($invitationResult) => new UserGroupInvitationResultDto($invitationResult));
    }
}
