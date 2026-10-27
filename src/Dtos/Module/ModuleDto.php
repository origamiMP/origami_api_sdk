<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class ModuleDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

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

    public bool $isActive;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'payment_psp',
            // 'webhooks',
            // 'logs',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                       => 'id',
            'name'                     => 'name',
            'current_version'          => 'currentVersion',
            'configurations'           => fn (object|array $configurations) => $this->configurations = (object) $configurations,
            'type'                     => 'type',
            'version'                  => 'version',
            'author'                   => 'author',
            'display_name'             => 'displayName',
            'description'              => 'description',
            'image'                    => 'imageUrl',
            'available_configurations' => fn (object|array $availableConfigurations) => $this->availableConfigurations = (object) $availableConfigurations,
            'is_active'                => 'isActive',
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'                       => ['required', 'integer'],
            'name'                     => ['required', 'string'],
            'current_version'          => ['required', 'string'],
            'configurations'           => ['present', 'array'],
            'type'                     => ['required', 'string'],
            'version'                  => ['required', 'string'],
            'author'                   => ['required', 'string'],
            'display_name'             => ['required', 'string'],
            'description'              => ['required', 'string'],
            'image'                    => ['required', 'string'],
            'available_configurations' => ['present', 'array'],
            'is_active'                => ['required', 'boolean'],
        ];

        return [
            ...$rules,
            ...$this->getTimestampsValidationRules(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ModuleDtoNotConstructableException($msg, previous: $previous);
    }
}
