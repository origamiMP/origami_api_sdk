<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionFileDtoNotConstructableException;

class FormSubmissionFileDto extends ApiResponseDto
{
    public string $name;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'name' => 'name',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'name' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSubmissionFileDtoNotConstructableException($msg, previous: $previous);
    }
}
