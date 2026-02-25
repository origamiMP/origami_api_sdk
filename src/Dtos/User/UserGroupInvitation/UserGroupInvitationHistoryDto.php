<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;

class UserGroupInvitationHistoryDto extends ApiResponseDto
{
    use HasAvailableIncludes;

    protected static array $availableIncludes = [
        'user_group' => UserGroupDto::class,
    ];

    /**
     * @var Collection|UserGroupInvitationDto[]
     */
    public Collection $data;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data' => ['present', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationHistoryResponseDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($invitationItem) => new UserGroupInvitationDto($invitationItem));
    }
}
