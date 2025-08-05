<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class ListUserGroupInvitationsRequestParamBag extends DataApiRequestParamBag
{
    // TODO Onboarding seller : Use trait for filtering (use it on every list param bag)
    public string $filterEmail;

    public string $filterStatus;

    // TODO Onboarding seller : Use trait for pagination (use it on every list param bag)
    public int $page = 1;

    public int $itemPerPage = 20;

    // TODO Onboarding seller : Use trait for searching (use it on every list param bag)
    public string $search;

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            [
                'filter',
                'page',
                'itemPerPage',
                'search',
            ],
        );
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'filterEmail'  => ['string', 'email'],
            'filterStatus' => ['string', 'in:pending,accepted,cancelled,expired'],
            'page'         => ['integer', 'min:1'],
            'itemPerPage'  => ['integer', 'min:1', 'max:100'],
            'search'       => ['string', 'nullable'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationDto::class;
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
