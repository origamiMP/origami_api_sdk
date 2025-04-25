<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

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
