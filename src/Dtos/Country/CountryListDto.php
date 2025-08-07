<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Country;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryListDtoNotConstructableException;

class CountryListDto extends ApiResponseDto
{
    /**
     * @var Collection|CountryDto[]
     */
    public Collection $data;

    public int $total;

    public int $count;

    public int $perPage;

    public int $currentPage;

    public int $totalPages;

    public array $links;

    protected function getDefaultDataStructureToProperties(): array
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
                    'links'        => fn ($links) => $this->initLinks($links),
                ],
            ],
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data'                         => ['required', 'array'],
            'meta'                         => ['required', 'array'],
            'meta.pagination'              => ['required', 'array'],
            'meta.pagination.total'        => ['required', 'integer'],
            'meta.pagination.count'        => ['required', 'integer'],
            'meta.pagination.per_page'     => ['required', 'integer'],
            'meta.pagination.current_page' => ['required', 'integer'],
            'meta.pagination.total_pages'  => ['required', 'integer'],
            'meta.pagination.links'        => ['present'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CountryListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($country) => new CountryDto($country));
    }

    protected function initLinks($links): void
    {
        if (is_object($links)) {
            $this->links = (array) $links;
        } else {
            $this->links = $links;
        }
    }
}
