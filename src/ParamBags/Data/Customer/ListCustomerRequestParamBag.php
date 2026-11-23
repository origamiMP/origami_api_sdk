<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasPagination as HasPaginationContract;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasSearch as HasSearchContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasPagination;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasSearch;

/**
 * The search looks for the name or the email of the customer, not her phone.
 */
class ListCustomerRequestParamBag extends RequestParamBag implements HasIncludesContract, HasPaginationContract, HasSearchContract
{
    use HasIncludes, HasPagination, HasSearch;

    protected function getQueryRequestParamsList(): array
    {
        return [
            ...$this->getPaginationParamsList(),
            ...$this->getSearchParamsList(),
            ...$this->getIncludesParamsList(),
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return CustomerListDto::class;
    }
}
