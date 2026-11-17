<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class RejectServiceAvailabilityReviewRequestParamBag extends RequestParamBag
{
    /** Why the availability is rejected, told to the provider. */
    public string $note;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'note' => 'note',
        ];
    }
}
