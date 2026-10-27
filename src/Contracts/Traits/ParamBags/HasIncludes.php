<?php

namespace OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags;

/**
 * Param bag sending the includes of a request, given by `HasIncludes`.
 */
interface HasIncludes
{
    public function setIncludes(array $includes): void;

    public function getIncludes(): array;
}
