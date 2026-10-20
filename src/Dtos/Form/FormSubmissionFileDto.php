<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionFileDtoNotConstructableException;

class FormSubmissionFileDto extends ApiResponseDto
{
    public string $name;

    public ?string $format;

    public ?int $size;

    /**
     * API route: it needs the caller's token.
     */
    public string $downloadUrl;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'name'         => 'name',
            'format'       => 'format',
            'size'         => 'size',
            'download_url' => 'downloadUrl',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'name'         => ['required', 'string'],
            'format'       => ['present', 'nullable', 'string'],
            'size'         => ['present', 'nullable', 'integer'],
            'download_url' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSubmissionFileDtoNotConstructableException($msg, previous: $previous);
    }
}
