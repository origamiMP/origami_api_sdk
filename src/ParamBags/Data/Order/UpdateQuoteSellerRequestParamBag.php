<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Order;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * Moves the quote to one of the next states the workflow allows the current user, as the back-office state buttons do.
 */
class UpdateQuoteSellerRequestParamBag extends RequestParamBag
{
    public int $stateId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'state_id' => 'stateId',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'state_id' => ['required', 'integer'],
        ];
    }
}
