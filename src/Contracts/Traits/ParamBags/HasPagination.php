<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

interface HasPagination
{
    public function withoutPagination(bool $withoutPagination = true): void;

    public function page(int $page = 1): void;

    public function perPage(int $perPage = 20): void;

    public function getPage(): int;

    public function getPerPage(): int;

    public function isWithoutPagination(): bool;
}
