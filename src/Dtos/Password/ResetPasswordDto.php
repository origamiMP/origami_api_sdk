<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Password;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\ResetPasswordDtoNotConstructableException;

class ResetPasswordDto extends ApiResponseDto
{
    /**
     * If the password was reset successfully.
     */
    public bool $success;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'success' => 'success',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'success' => ['required', 'boolean'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ResetPasswordDtoNotConstructableException($msg, previous: $previous);
    }
}
