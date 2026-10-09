<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPrefillSourcesDtoNotConstructableException;

/**
 * Reads the `data` key of the response of `GET forms/prefill-sources` (or the payload itself when already unwrapped).
 */
class FormPrefillSourcesDto extends ApiResponseDto implements Arrayable
{
    /** @var Collection<int, FormPrefillSourceDto> */
    public Collection $attributes;

    /** @var Collection<int, FormPrefillSourceDto> */
    public Collection $customFields;

    protected function getDataToValidateAndFillFrom(): object
    {
        return $this->apiResponse->data ?? $this->apiResponse;
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'attributes'    => fn (array $attributes) => $this->attributes = $this->mapSources($attributes),
            'custom_fields' => fn (array $customFields) => $this->customFields = $this->mapSources($customFields),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'attributes'    => ['present', 'array'],
            'custom_fields' => ['present', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormPrefillSourcesDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }

    /** @return Collection<int, FormPrefillSourceDto> */
    private function mapSources(array $sources): Collection
    {
        return collect($sources)->map(fn (object $source) => new FormPrefillSourceDto($source))->values();
    }
}
