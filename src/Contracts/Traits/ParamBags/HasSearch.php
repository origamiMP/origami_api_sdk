<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

interface HasSearch
{
    public function search(string $search): void;

    public function getSearch(): string;
}
