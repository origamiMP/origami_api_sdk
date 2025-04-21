<?php

namespace OrigamiMp\OrigamiApiSdk\Traits;

trait HasCorrespondingException
{
    /**
     * @throws \Throwable
     */
    public function throwCorrespondingException(): void
    {
        throw $this->getCorrespondingException();
    }

    abstract public function getCorrespondingException(): \Throwable;
}
