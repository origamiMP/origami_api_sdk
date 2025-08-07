<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

use Illuminate\Support\Collection;

trait HasPagination
{
    public Collection $data;

    public int $total;

    public int $count;

    public int $perPage;

    public int $currentPage;

    public int $totalPages;

    protected function getPaginationAsDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
            'meta' => [
                'pagination' => [
                    'total'        => 'total',
                    'count'        => 'count',
                    'per_page'     => 'perPage',
                    'current_page' => 'currentPage',
                    'total_pages'  => 'totalPages',
                ],
            ],
        ];
    }

    protected function getPaginationValidationRules(): array
    {
        return [
            'data'                         => ['present', 'array'],
            'meta'                         => ['required', 'array'],
            'meta.pagination'              => ['required', 'array'],
            'meta.pagination.total'        => ['required', 'integer'],
            'meta.pagination.count'        => ['required', 'integer'],
            'meta.pagination.per_page'     => ['required', 'integer'],
            'meta.pagination.current_page' => ['required', 'integer'],
            'meta.pagination.total_pages'  => ['required', 'integer'],
        ];
    }

    abstract protected function initData(array $data): void;
}
