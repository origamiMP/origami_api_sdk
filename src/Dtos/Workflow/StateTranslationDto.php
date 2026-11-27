<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Workflow;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Workflow\StateTranslationDtoNotConstructableException;

class StateTranslationDto extends ApiResponseDto
{
    public ?string $name;

    public ?string $actionName;

    public ?string $description;

    public bool $default;

    public string $locale;

    public int $languageId;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'name'        => 'name',
            'action_name' => 'actionName',
            'description' => 'description',
            'default'     => 'default',
            'locale'      => 'locale',
            'language_id' => 'languageId',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new StateTranslationDtoNotConstructableException($msg, previous: $previous);
    }
}
