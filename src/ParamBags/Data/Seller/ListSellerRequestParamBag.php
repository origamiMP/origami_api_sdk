<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasFilters as HasFiltersContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListSellerRequestParamBag extends RequestParamBag implements HasFiltersContract, HasIncludesContract, HasPaginationContract, HasSearchContract
{
    use HasFilters, HasIncludes, HasPagination, HasSearch;

    protected static function getAdditionalAvailableFilters(): array
    {
        return [
            'without_phone',
            'address_city',
            'external_uid',
            'seller_type',
            'legal_type',
            'siret',
            'additional_information',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getFiltersParamsList(),
            ...$this->getPaginationParamsList(),
            ...$this->getSearchParamsList(),
            ...$this->getIncludesParamsList(),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            ...$this->getFiltersValidationRules(),
            ...$this->getPaginationValidationRules(),
            ...$this->getSearchValidationRules(),
            ...$this->getIncludesValidationRules(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return SellerListDto::class;
    }
}
