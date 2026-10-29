<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

/**
 * Param bag sending the pagination of a list, given by `HasPagination`.
 */
interface HasPagination
{
    public function setWithoutPagination(bool $withoutPagination): void;

    public function setPage(int $page): void;

    public function setPerPage(int $perPage): void;

    public function getPage(): int;

    public function getPerPage(): int;

    public function isWithoutPagination(): bool;
}
