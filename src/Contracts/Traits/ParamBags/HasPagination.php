<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

interface HasPagination
{
    public function setWithoutPagination(bool $withoutPagination): void;

    public function setPage(int $page): void;

    public function setPerPage(int $perPage): void;

    public function getPage(): int;

    public function getPerPage(): int;

    public function isWithoutPagination(): bool;
}
