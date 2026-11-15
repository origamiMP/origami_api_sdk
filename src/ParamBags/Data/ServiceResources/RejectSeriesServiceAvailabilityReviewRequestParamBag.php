<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class RejectSeriesServiceAvailabilityReviewRequestParamBag extends RequestParamBag
{
    /** Why the regular presence is rejected, told to the provider for every date. */
    public string $note;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'note' => 'note',
        ];
    }
}
