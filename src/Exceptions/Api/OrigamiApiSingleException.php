<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Api;

use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorDto;

abstract class OrigamiApiSingleException extends OrigamiApiException
{
    public function __construct(protected OrigamiApiErrorDto $errorDto, $previous = null)
    {
        parent::__construct($this->errorDto->toString(), previous:  $previous);
    }

    public function getOrigamiApiErrorCode(): string
    {
        return $this->errorDto->errorCode;
    }
}
