<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldValueListDtoNotConstructableException;

/**
 * `GET custom_fields/{id}/values` returns every possible value in a single, unpaginated
 * response (no `meta.pagination`) — unlike the other list endpoints in this SDK.
 */
class CustomFieldValueListDto extends ApiResponseDto
{
    public Collection $data;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data' => ['present', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldValueListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($customFieldValue) => new CustomFieldValueDto($customFieldValue));
    }
}
