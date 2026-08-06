<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldValueDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * Represents one possible value of a `select`/`multiple` Custom Field definition,
 * as returned by `GET custom_fields/{id}/values`.
 */
class CustomFieldValueDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    public string $value;

    public int $customFieldId;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'              => 'id',
            'value'           => 'value',
            'custom_field_id' => 'customFieldId',
        ];

        return array_merge($structure, $this->getTimestampsAsDataStructureToProperties());
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'              => ['required', 'integer'],
            'value'           => ['required', 'string'],
            'custom_field_id' => ['required', 'integer'],
        ];

        return array_merge($rules, $this->getTimestampsValidationRules());
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldValueDtoNotConstructableException($msg, previous: $previous);
    }
}
