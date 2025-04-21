<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Api;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorDto;

class OrigamiApiMultipleException extends OrigamiApiException
{
    /**
     * @param  Collection|OrigamiApiErrorDto[]  $errorDtos
     */
    public function __construct(protected Collection $errorDtos)
    {
        $msg = '';

        foreach ($errorDtos as $errorDto) {
            $msg .= "{$errorDto->toString()}\n";
        }

        parent::__construct($msg);
    }

    public function getErrorDtos(): Collection
    {
        return $this->errorDtos;
    }
}
