<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldTranslationDtoNotConstructableException;

class CustomFieldTranslationDto extends ApiResponseDto
{
    public int $languageId;

    public string $locale;

    public string $name;

    public string $description;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'language_id' => 'languageId',
            'locale'      => 'locale',
            'name'        => 'name',
            'description' => 'description',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'language_id' => ['required', 'integer'],
            'locale'      => ['required', 'string'],
            'name'        => ['present', 'string'],
            'description' => ['present', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
