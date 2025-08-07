<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\ParamBags;

trait HasPagination
{
    public bool $withoutPagination;

    public int $page = 1;

    public int $itemPerPage = 20;

    public function withoutPagination(bool $withoutPagination = true): void
    {
        if ($withoutPagination) {
            $this->withoutPagination = true;
        } else {
            unset($this->withoutPagination);
        }
    }

    public function page(int $page = 1): void
    {
        $this->page = $page;
    }

    public function perPage(int $perPage = 20): void
    {
        $this->itemPerPage = min(100, $perPage);
    }

    protected function getPaginationParamsList(): array
    {
        return ['page', 'itemPerPage', 'withoutPagination'];
    }

    protected function getPaginationValidationRules(): array
    {
        return [
            'page'              => ['integer', 'min:1'],
            'itemPerPage'       => ['integer', 'min:1', 'max:100'],
            'withoutPagination' => ['boolean'],
        ];
    }
}
