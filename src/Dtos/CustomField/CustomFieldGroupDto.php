<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldGroupDtoNotConstructableException;

class CustomFieldGroupDto extends ApiResponseDto
{
    public string $key;

    /**
     * @var Collection|CustomFieldGroupTranslationDto[]
     */
    public Collection $translations;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'key'          => 'key',
            'translations' => fn ($translations) => $this->initTranslations($translations),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'key'          => ['required', 'string'],
            'translations' => ['required', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldGroupDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initTranslations(array $translations): void
    {
        $this->translations = collect($translations)->map(fn ($translation) => new CustomFieldGroupTranslationDto($translation));
    }
}
