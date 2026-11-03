<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPrefillSourcesDtoNotConstructableException;

class FormPrefillSourcesDto extends ApiResponseDto
{
    /** @var Collection<int, FormPrefillSourceDto> */
    public Collection $attributes;

    /** @var Collection<int, FormPrefillSourceDto> */
    public Collection $customFields;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'attributes'    => fn (array $attributes) => $this->initAttributes($attributes),
            'custom_fields' => fn (array $customFields) => $this->initCustomFields($customFields),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormPrefillSourcesDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initAttributes(array $attributes): void
    {
        $this->attributes = collect($attributes)->map(fn (object $attribute) => new FormPrefillSourceDto($attribute));
    }

    protected function initCustomFields(array $customFields): void
    {
        $this->customFields = collect($customFields)->map(fn (object $customField) => new FormPrefillSourceDto($customField));
    }
}
