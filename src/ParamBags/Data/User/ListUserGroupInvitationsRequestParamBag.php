<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListUserGroupInvitationsRequestParamBag extends RequestParamBag
{
    use HasFilters, HasIncludes, HasPagination, HasSearch;

    protected static array $availableFilters = [
        'user_group_id',
        'status',
        'email',
        'token_expires_at',
        'onboarding_url',
        'sent_at',
        'accepted_at',
    ];

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getFiltersParamsList(),
            $this->getPaginationParamsList(),
            $this->getSearchParamsList(),
            $this->getIncludeParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return array_merge(
            $this->getFiltersValidationRules(),
            $this->getPaginationValidationRules(),
            $this->getSearchValidationRules(),
            $this->getIncludeValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return UserGroupInvitationDto::class;
    }
}
