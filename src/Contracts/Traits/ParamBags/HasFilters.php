<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

interface HasFilters
{
    public static function getAvailableFilters(): array;

    public function withFilters(array $filters = []): void;

    public function getFilters(): array;
}
