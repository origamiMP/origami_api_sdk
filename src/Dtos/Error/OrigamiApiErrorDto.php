<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Error;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Error\OrigamiApiErrorCodeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\Oauth\OrigamiApiUnauthorizedException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiSingleException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Error\OrigamiApiErrorDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCorrespondingException;

class OrigamiApiErrorDto extends ApiResponseDto
{
    use HasCorrespondingException;

    public int $httpStatusCode;

    public string $message;

    public string $errorCode;

    /**
     * @throws OrigamiApiErrorDtoNotConstructableException
     */
    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    public function getCorrespondingException(): OrigamiApiSingleException
    {
        $errorCodeException = $this->getCorrespondingExceptionToErrorCode();

        if (! is_null($errorCodeException)) {
            return $errorCodeException;
        }

        return $this->getCorrespondingExceptionToHttpStatusCode();
    }

    public function toString(): string
    {
        $msg = "[HTTP {$this->httpStatusCode}]";

        if ($this->errorCode !== '0') {
            $msg .= " Error code {$this->errorCode} -";
        }

        return "$msg {$this->message}";
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'status' => 'httpStatusCode',
            'detail' => 'message',
            'code'   => 'errorCode',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'status' => ['required', 'integer'],
            'detail' => ['required', 'string'],
            'code'   => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new OrigamiApiErrorDtoNotConstructableException($msg, previous: $previous);
    }

    protected function getCorrespondingExceptionToErrorCode(): ?OrigamiApiSingleException
    {
        return match (OrigamiApiErrorCodeEnum::tryFrom($this->errorCode)) {
            OrigamiApiErrorCodeEnum::UNAUTHORIZED => new OrigamiApiUnauthorizedException($this),

            default => null,
        };
    }

    protected function getCorrespondingExceptionToHttpStatusCode(): OrigamiApiSingleException|OrigamiApiUnknownException
    {
        return match ($this->httpStatusCode) {
            401 => new OrigamiApiUnauthorizedException($this),

            default => OrigamiApiUnknownException::createFromUnknownOrigamiApiHttpStatusCode($this),
        };
    }
}
