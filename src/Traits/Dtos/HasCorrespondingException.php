<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiException;

trait HasCorrespondingException
{
    /**
     * @throws OrigamiApiException
     */
    public function throwCorrespondingException(): void
    {
        throw $this->getCorrespondingException();
    }

    abstract public function getCorrespondingException(): \Throwable;
}
