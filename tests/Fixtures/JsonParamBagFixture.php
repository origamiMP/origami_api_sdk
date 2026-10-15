<?php

namespace Tests\Fixtures;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * Minimal param bag sending its properties as JSON, to test how every param bag turns its values into JSON.
 */
class JsonParamBagFixture extends RequestParamBag
{
    public Carbon $date;

    protected function getJsonRequestParamsList(): array
    {
        return ['date'];
    }

    protected function validationRulesForProperties(): array
    {
        return [];
    }
}
