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

    public Carbon $dateOnly;

    /** Sent through a cast returning the property value itself, to set what a cast returns. */
    public mixed $castResult;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'date'       => 'date',
            'dateOnly'   => fn (Carbon $date) => ['date_only' => $date->format('Y-m-d')],
            'castResult' => fn (mixed $castResult) => $castResult,
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [];
    }
}
