<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationSendDtoNotConstructableException;

class UserGroupInvitationSendDto extends ApiResponseDto
{
    /** @var Collection<int, UserGroupInvitationResultDto> */
    public Collection $data;

    public string $message;

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
            'data'    => ['present', 'array'],
            'message' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationSendDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($invitationResult) => new UserGroupInvitationResultDto($invitationResult));
    }
}
