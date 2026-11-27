<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class GetCustomerRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludesParamsList();
    }

    protected static function getRequestMainDto(): string
    {
        return CustomerDto::class;
    }
}
