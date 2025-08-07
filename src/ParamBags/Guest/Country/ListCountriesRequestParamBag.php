<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Country;

use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

class ListCountriesRequestParamBag extends RequestParamBag
{
    use HasPagination, HasSearch;

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            $this->getPaginationParamsList(),
            $this->getSearchParamsList(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        return array_merge(
            $this->getPaginationValidationRules(),
            $this->getSearchValidationRules(),
        );
    }
}
