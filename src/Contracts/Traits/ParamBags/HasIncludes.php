<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

interface HasIncludes
{
    public function setIncludes(array $includes): void;

    public function getIncludes(): array;
}
