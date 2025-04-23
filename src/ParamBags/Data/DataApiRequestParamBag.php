<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

abstract class DataApiRequestParamBag extends RequestParamBag
{
    public array $availableIncludes = [];

    public array $include = [];

    /**
     * @param  string[]  $includes  Array of includes as strings. Ex : ['products', 'offers']
     */
    public function setIncludes(array $includes): void
    {
        $this->include = collect($includes)->values()->unique()->intersect($this->availableIncludes)->toArray();
    }

    protected static function propertiesToExcludeFromGuzzleParams(): array
    {
        return ['availableIncludes'];
    }

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            ['include'],
        );
    }
}
