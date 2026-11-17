<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class GetServiceSiteBoardsRequestParamBag extends RequestParamBag
{
    /**
     * The places to read the board of.
     *
     * @var int[]
     */
    public array $ids;

    /** A day `Y-m-d`, today by default. */
    public string $from;

    /** A day `Y-m-d`, the `from` day by default. */
    public string $to;

    protected function getQueryRequestParamsList(): array
    {
        return [
            'ids'  => 'ids',
            'from' => 'from',
            'to'   => 'to',
        ];
    }
}
