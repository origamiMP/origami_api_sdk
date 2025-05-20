<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;

abstract class DataApiRequestParamBag extends RequestParamBag
{
    public array $include = [];

    /**
     * @param  string[]  $includes  Array of includes as strings. Ex : ['products', 'offers.seller']
     */
    public function setIncludes(array $includes): void
    {
        /**
         * @var $requestMainDto class-string<HasAvailableIncludes>
         */
        $requestMainDto = static::getRequestMainDto();

        if (! method_exists($requestMainDto, 'isIncludeAvailable')) {
            return;
        }

        $this->include = collect($includes)
            ->values()
            ->unique()
            ->filter(fn ($include) => $requestMainDto::isIncludeAvailable($include))
            ->toArray();
    }

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            ['include'],
        );
    }

    /**
     * If this DataApiRequestParamBag is used in a request that will return a certain DTO,
     * return it here.
     * Example : UserDto::class
     */
    abstract protected static function getRequestMainDto(): string;
}
