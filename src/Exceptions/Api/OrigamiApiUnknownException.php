<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Api;

use GuzzleHttp\Exception\BadResponseException;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorDto;
use Psr\Http\Message\ResponseInterface;

class OrigamiApiUnknownException extends OrigamiApiException
{
    public ?OrigamiApiErrorDto $errorDto;

    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null, ?OrigamiApiErrorDto $errorDto = null)
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

    /**
     * $expected is the JSON type the body should have been (ex: object, list), null when it is not JSON at all.
     */
    public static function createFromUnreadableResponse(
        ResponseInterface $response,
        ?string $expected = null,
        ?\Throwable $previous = null,
    ): self {
        $reason = is_null($expected) ? 'the body is not valid JSON' : "the body is not a JSON $expected";
        $msg = "Unreadable response from Origami API : HTTP {$response->getStatusCode()}, $reason";

        return new OrigamiApiUnknownException($msg, previous: $previous);
    }
}
