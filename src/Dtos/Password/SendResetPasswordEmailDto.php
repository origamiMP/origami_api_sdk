<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Password;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\SendResetPasswordEmailDtoNotConstructableException;

class SendResetPasswordEmailDto extends ApiResponseDto
{
    /**
     * If the email was sent successfully.
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
        return new SendResetPasswordEmailDtoNotConstructableException($msg, previous: $previous);
    }
}
