<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Error;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Error\OrigamiApiErrorDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\HasCorrespondingException;

class OrigamiApiErrorDto extends ApiResponseDto
{
    use HasCorrespondingException;

    public int $statusCode;

    public string $detail;

    public string $code;

    public static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new OrigamiApiErrorDtoNotConstructableException($msg, previous: $previous);
    }

    public function getDefaultDataStructureToProperties(): array
    {
        return [
            'status' => 'statusCode',
            'detail' => 'detail',
            'code'   => 'code',
        ];
    }

    public function throwCorrespondingException(): void
    {
        // TODO
    }
}
