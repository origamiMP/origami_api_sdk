<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationListResponseDtoNotConstructableException;

class UserGroupInvitationListDto extends ApiResponseDto
{
    /**
     * @var Collection|UserGroupInvitationDto[]
     */
    public Collection $data;

    // TODO Onboarding seller : Use trait for pagination (use it on every list dto)

    public int $total;

    public int $count;

    public int $perPage;

    public int $currentPage;

    public int $totalPages;

    public array $links;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
            'meta' => [
                'pagination' => [
                    'total'        => 'total',
                    'count'        => 'count',
                    'per_page'     => 'perPage',
                    'current_page' => 'currentPage',
                    'total_pages'  => 'totalPages',
                    'links'        => fn ($links) => $this->initLinks($links),
                ],
            ],
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data' => ['array'],
            'meta' => [
                'pagination' => [
                    'total'        => ['required', 'integer', 'min:0'],
                    'count'        => ['required', 'integer', 'min:0'],
                    'per_page'     => ['required', 'integer', 'min:0'],
                    'current_page' => ['required', 'integer', 'min:1'],
                    'total_pages'  => ['required', 'integer', 'min:1'],
                    'links'        => ['required', 'array'],
                ],
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
        $this->data = collect($data)->map(fn ($invitationItem) => new UserGroupInvitationDto($invitationItem));
    }

    protected function initLinks(object|array $links): void
    {
        // Convertir l'objet en array si nécessaire
        $this->links = is_object($links) ? (array) $links : $links;
    }
}
