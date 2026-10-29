<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\ParamBags;

trait HasPagination
{
    private bool $withoutPagination;

    private int $page = 1;

    private int $itemPerPage = 20;

    public function setWithoutPagination(bool $withoutPagination): void
    {
        if ($withoutPagination) {
            $this->withoutPagination = true;
        } else {
            unset($this->withoutPagination);
        }
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function setPerPage(int $perPage): void
    {
        $this->itemPerPage = min(100, $perPage);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->itemPerPage;
    }

    public function isWithoutPagination(): bool
    {
        return isset($this->withoutPagination) && $this->withoutPagination;
    }

    protected function getPaginationParamsList(): array
    {
        return ['page' => 'page', 'itemPerPage' => 'item_per_page', 'withoutPagination' => 'without_pagination'];
    }
}
