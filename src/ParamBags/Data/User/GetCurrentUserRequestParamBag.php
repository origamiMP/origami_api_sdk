<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class GetCurrentUserRequestParamBag extends RequestParamBag implements HasFiltersContract, HasIncludesContract
{
    use HasFilters, HasIncludes;

    /**
     * roles_user_group_id restricts the included roles to the ones of the User in that UserGroup.
     */
    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'roles_user_group_id',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            $this->getFiltersParamsList(),
            $this->getIncludeParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return array_merge(
            $this->getFiltersValidationRules(),
            $this->getIncludeValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return UserDto::class;
    }
}
