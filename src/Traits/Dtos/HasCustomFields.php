<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldDto;

trait HasCustomFields
{
    /**
     * @var Collection|CustomFieldDto[]
     */
    public Collection $additionalInformation;

    protected function getCustomFieldsAsDataStructureToProperties(): array
    {
        return [
            'additional_information' => fn ($additionalInformation) => $this->initAdditionalInformation($additionalInformation),
        ];
    }

    protected function getCustomFieldsValidationRules(): array
    {
        return [
            'additional_information' => ['array'],
        ];
    }

    protected function initAdditionalInformation(object $additionalInformation): void
    {
        $this->additionalInformation = collect($additionalInformation)->map(fn ($customField) => new CustomFieldDto($customField));
    }
}
