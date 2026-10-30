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
            'configurations'           => 'configurations',
            'type'                     => 'type',
            'version'                  => 'version',
            'author'                   => 'author',
            'display_name'             => 'displayName',
            'description'              => 'description',
            'image'                    => 'imageUrl',
            'available_configurations' => 'availableConfigurations',
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ModuleDtoNotConstructableException($msg, previous: $previous);
    }
}
