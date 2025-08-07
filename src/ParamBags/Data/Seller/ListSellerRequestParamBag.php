<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasFilters;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListSellerRequestParamBag extends RequestParamBag
{
    use HasFilters, HasIncludes, HasPagination, HasSearch;

    protected static array $availableFilters = [
        'without_phone',
        'address_city',
        'external_uid',
        'seller_type',
        'legal_type',
        'siret',
    ];

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getFiltersParamsList(),
            $this->getPaginationParamsList(),
            $this->getSearchParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return array_merge(
            $this->getFiltersValidationRules(),
            $this->getPaginationValidationRules(),
            $this->getSearchValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return SellerListDto::class;
    }
}
