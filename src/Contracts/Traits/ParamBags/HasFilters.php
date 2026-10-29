<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

/**
 * Param bag sending the filters of a list, given by `HasFilters`.
 */
interface HasFilters
{
    public static function getAvailableFilters(): array;

    public function setFilters(array $filters): void;

    public function getFilters(): array;
}
