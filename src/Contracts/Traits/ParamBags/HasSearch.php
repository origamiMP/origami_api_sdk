<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

interface HasSearch
{
    public function setSearch(string $search): void;

    public function getSearch(): string;
}
