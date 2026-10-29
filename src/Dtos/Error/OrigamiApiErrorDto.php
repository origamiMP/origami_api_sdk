<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Error;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Error\OrigamiApiErrorCodeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiBadRequestException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiClientErrorException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiConflictException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiForbiddenException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiNotFoundException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiSingleException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiTooManyRequestsException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnauthorizedException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnprocessableEntityException;
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
     * Name of the request field this error is about, when the API provides it in `data.field`.
     */
    public ?string $field = null;

    public function getCorrespondingException(): OrigamiApiSingleException|OrigamiApiUnknownException
    {
        return $this->getCorrespondingExceptionToErrorCode()
            ?: $this->getCorrespondingExceptionToHttpStatusCode();
    }

    public function toString(): string
    {
        $msg = "[HTTP {$this->httpStatusCode}]";

        if ($this->errorCode !== '0') {
            $msg .= " Error code {$this->errorCode} -";
        }

        $msg .= " {$this->message}";

        if (! is_null($this->field)) {
            $msg .= " (field: {$this->field})";
        }

        return $msg;
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'status' => 'httpStatusCode',
            'detail' => 'message',
            'code'   => 'errorCode',
            'data'   => [
                'field' => 'field',
            ],
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'status'     => ['required', 'integer'],
            'detail'     => ['required', 'string'],
            'code'       => ['required', 'string'],
            'data.field' => ['sometimes', 'nullable', 'string'],
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
        return match (true) {
            $this->httpStatusCode === 400 => new OrigamiApiBadRequestException($this),
            $this->httpStatusCode === 401 => new OrigamiApiUnauthorizedException($this),
            $this->httpStatusCode === 403 => new OrigamiApiForbiddenException($this),
            $this->httpStatusCode === 404 => new OrigamiApiNotFoundException($this),
            $this->httpStatusCode === 409 => new OrigamiApiConflictException($this),
            $this->httpStatusCode === 422 => new OrigamiApiUnprocessableEntityException($this),
            $this->httpStatusCode === 429 => new OrigamiApiTooManyRequestsException($this),

            $this->httpStatusCode >= 400 && $this->httpStatusCode < 500 => new OrigamiApiClientErrorException($this),

            default => OrigamiApiUnknownException::createFromUnknownOrigamiApiHttpStatusCode($this),
        };
    }
}
