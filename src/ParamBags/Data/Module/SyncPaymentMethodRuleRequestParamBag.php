<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module;

use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\SyncPaymentMethodRule\SyncPaymentMethodRuleParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class SyncPaymentMethodRuleRequestParamBag extends RequestParamBag
{
    /** @var array<int, SyncPaymentMethodRuleParamBag> */
    public array $rules;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'rules' => 'rules',
        ];
    }
}
