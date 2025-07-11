<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationListResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class GetUserGroupInvitationsListRequestParamBag extends DataApiRequestParamBag
{
    public string $filterEmail = '';

    public string $filterStatus = '';

    public int $page = 1;

    public int $perPage = 20;

    public string $search = '';

    protected function getQueryRequestParamsList(): array
    {
        return [
            'filter',
            'page',
            'per_page',
            'search',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'filter_email'  => ['present', 'string', 'email'],
            'filter_status' => ['present', 'string', 'in:pending,accepted,cancelled,expired'],
            'page'          => ['present', 'integer', 'min:1'],
            'per_page'      => ['present', 'integer', 'min:1', 'max:100'],
            'search'        => ['present', 'string'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationListResponseDto::class;
    }

    protected function asEncodableArray(?array $propertiesList = null): array
    {
        $array = parent::asEncodableArray($propertiesList);

        // Handle filter parameters
        if (! empty($this->filterEmail)) {
            $array['filter']['email'] = $this->filterEmail;
        }

        if (! empty($this->filterStatus)) {
            $array['filter']['status'] = $this->filterStatus;
        }

        return $array;
    }
}
