<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

/**
 * Param bag sending the search of a list, given by `HasSearch`.
 */
interface HasSearch
{
    public function setSearch(string $search): void;

    public function getSearch(): string;
}
