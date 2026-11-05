<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCustomFields;

/**
 * Minimal Dto with custom fields, to test the HasCustomFields trait: it only reads an `id` along with the trait's
 * `additional_information`.
 */
class CustomFieldsDtoFixture extends ApiResponseDto
{
    use HasCustomFields;

    public int $id;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id' => 'id',
            ...$this->getCustomFieldsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new class($msg, previous: $previous) extends ApiResponseDtoNotConstructableException {};
    }
}
