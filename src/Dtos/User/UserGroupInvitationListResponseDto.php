<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationListResponseDtoNotConstructableException;

class UserGroupInvitationListResponseDto extends ApiResponseDto
{
    /**
     * @var Collection|UserGroupInvitationListItemDto[]
     */
    public Collection $data;

    public int $currentPage;

    public int $perPage;

    public int $total;

    public int $lastPage;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
            'meta' => [
                'current_page' => 'currentPage',
                'per_page'     => 'perPage',
                'total'        => 'total',
                'last_page'    => 'lastPage',
            ],
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data' => ['required', 'array'],
            'meta' => [
                'current_page' => ['required', 'integer', 'min:1'],
                'per_page'     => ['required', 'integer', 'min:1'],
                'total'        => ['required', 'integer', 'min:0'],
                'last_page'    => ['required', 'integer', 'min:1'],
            ],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupInvitationListResponseDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($invitationItem) => new UserGroupInvitationListItemDto($invitationItem));
    }
}
