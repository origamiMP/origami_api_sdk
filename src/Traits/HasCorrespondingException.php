<?php

namespace OrigamiMp\OrigamiApiSdk\Traits;

trait HasCorrespondingException
{
    abstract public function throwCorrespondingException(): void;
}
