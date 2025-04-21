<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Api;

use GuzzleHttp\Exception\BadResponseException;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorDto;

class OrigamiApiUnknownException extends OrigamiApiException
{
    public ?OrigamiApiErrorDto $errorDto;

    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, ?OrigamiApiErrorDto $errorDto = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorDto = $errorDto;
    }

    public static function createFromGuzzleBadResponse(BadResponseException $exception): self
    {
        $msg = "Unknown error from Origami API : {$exception->getMessage()}";

        return new OrigamiApiUnknownException($msg, previous: $exception);
    }

    public static function createFromUnknownOrigamiApiHttpStatusCode(OrigamiApiErrorDto $errorDto): self
    {
        $msg = "Error from Origami API with unregistered error code or http code : {$errorDto->toString()}";

        return new OrigamiApiUnknownException($msg, errorDto: $errorDto);
    }
}
