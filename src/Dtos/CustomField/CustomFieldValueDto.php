<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldValueDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * Represents one possible value of a `select`/`multiple` Custom Field definition,
 * as returned by `GET custom_fields/{id}/values`.
 */
class CustomFieldValueDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public string $value;

    public int $customFieldId;

    /**
     * May be undefined if the corresponding data was not included.
     */
    public CustomFieldDto $customField;

    public static function getAvailableIncludes(): array
    {
        return [
            'custom_field' => CustomFieldDto::class,
            // 'translations',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'              => 'id',
            'value'           => 'value',
            'custom_field_id' => 'customFieldId',

            'custom_field' => fn (object $customField) => $this->customField = $this->makeIncluded($customField, CustomFieldDto::class),
        ];

        return [...$structure, ...$this->getTimestampsAsDataStructureToProperties()];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldValueDtoNotConstructableException($msg, previous: $previous);
    }
}
