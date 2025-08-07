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

    public array $filter;

    /**
     * Available filters for this specific request. They should be declared this way :
     * protected static array $availableFilters = [
     *      'createdBefore',
     *      'createdAfter',
     * ]
     *
     * @return string[]
     */
    public static function getAvailableFilters(): array
    {
        return array_merge(
            self::BASE_FILTERS,
            static::$availableFilters ?? [],
        );
    }

    public function withFilters(array $filters = []): void
    {
        $this->filter = collect($filters)
            ->filter(fn ($filterValue, $filterKey) => in_array($filterKey, static::getAvailableFilters()))
            ->toArray();
    }

    protected function getFiltersParamsList(): array
    {
        return ['filter'];
    }

    protected function getFiltersValidationRules(): array
    {
        return [
            'filter' => ['array'],
        ];
    }
}
