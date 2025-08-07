<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\ParamBags;

trait HasIncludes
{
    public array $include = [];

    /**
     * @param  string[]  $includes  Array of includes as strings. Ex : ['products', 'offers.seller']
     */
    public function setIncludes(array $includes): void
    {
        /**
         * @var string $requestMainDto
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

    protected function getIncludeParamsList(): array
    {
        return ['include'];
    }

    protected function getIncludeValidationRules(): array
    {
        return [
            'include' => ['array']
        ];
    }

    /**
     * If this DataApiRequestParamBag is used in a request that will return a certain DTO,
     * return it here.
     * Example : UserDto::class
     */
    abstract protected static function getRequestMainDto(): string;
}
