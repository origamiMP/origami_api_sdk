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

    private array $filter;

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
            self::getAdditionalAvailableFilters(),
        );
    }

    public function withFilters(array $filters = []): void
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
        return ['filter'];
    }

    protected function getFiltersValidationRules(): array
    {
        return [
            'filter' => ['array'],
        ];
    }

    abstract protected static function getAdditionalAvailableFilters(): array;
}
