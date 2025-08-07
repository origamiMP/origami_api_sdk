<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class ModuleDto extends ApiResponseDto
{
    use HasAvailableIncludes, HasTimestamps;

    protected static array $availableIncludes = [
        // 'payment_psp',
        // 'webhooks',
        // 'logs',
    ];

    public int $id;

    public string $name;

    public string $currentVersion;

    public object $configurations;

    public string $type;

    public string $version;

    public string $author;

    public string $displayName;

    public string $description;

    public string $imageUrl;

    public object $availableConfigurations;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                       => 'id',
            'name'                     => 'name',
            'current_version'          => 'currentVersion',
            'configurations'           => 'configurations',
            'type'                     => 'type',
            'version'                  => 'version',
            'author'                   => 'author',
            'display_name'             => 'displayName',
            'description'              => 'description',
            'image'                    => 'imageUrl',
            'available_configurations' => 'availableConfigurations',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'                       => ['required', 'integer'],
            'name'                     => ['required', 'string'],
            'current_version'          => ['required', 'string'],
            'configurations'           => ['required', 'array'],
            'type'                     => ['required', 'string'],
            'version'                  => ['required', 'string'],
            'author'                   => ['required', 'string'],
            'display_name'             => ['required', 'string'],
            'description'              => ['required', 'string'],
            'image'                    => ['required', 'string'],
            'available_configurations' => ['required', 'array'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ModuleDtoNotConstructableException($msg, previous: $previous);
    }
}
