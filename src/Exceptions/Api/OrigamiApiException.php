<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Api;

use GuzzleHttp\Exception\BadResponseException;
use Throwable;

abstract class OrigamiApiException extends \Exception
{
    // TODO

    private function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function createFromGuzzleBadResponse(BadResponseException $exception): self {}
}
