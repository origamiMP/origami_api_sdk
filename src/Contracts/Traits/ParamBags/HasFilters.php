<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

interface HasFilters
{
    public static function getAvailableFilters(): array;

    public function setFilters(array $filters): void;

    public function getFilters(): array;
}
