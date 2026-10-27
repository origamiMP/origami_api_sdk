<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\ParamBags;

trait HasFilters
{
    const BASE_FILTERS = [
        'createdBefore',
        'createdAfter',
        'updatedBefore',
        'updatedAfter',
    ];

    private array $filter = [];

    /**
     * Available filters for this specific request: the date filters of `BASE_FILTERS`, plus the ones the param bag
     * declares in `getAdditionalAvailableFilters()`.
     *
     * @return string[]
     */
    public static function getAvailableFilters(): array
    {
        return [
            ...self::BASE_FILTERS,
            ...self::getAdditionalAvailableFilters(),
        ];
    }

    public function setFilters(array $filters): void
    {
        $this->filter = collect($filters)
            ->filter(fn ($filterValue, $filterKey) => in_array($filterKey, static::getAvailableFilters()))
            ->toArray();
    }

    public function getFilters(): array
    {
        return $this->filter;
    }

    protected function getFiltersParamsList(): array
    {
        return ['filter' => 'filter'];
    }

    protected function getFiltersValidationRules(): array
    {
        return [
            'filter' => ['array'],
        ];
    }

    abstract protected static function getAdditionalAvailableFilters(): array;
}
