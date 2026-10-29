<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\RoleDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RoleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class RoleDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public string $name;

    public RoleDtoTypeEnum $type;

    public string $color;

    public string $textColor;

    /**
     * Permissions granted by this Role.
     *
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, RolePermissionDto>
     */
    public Collection $permissions;

    /**
     * Users having this Role.
     *
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, UserDto>
     */
    public Collection $users;

    public static function getAvailableIncludes(): array
    {
        return [
            'permissions' => RolePermissionDto::class,
            'users'       => UserDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'         => 'id',
            'name'       => 'name',
            'type'       => fn ($type) => $this->type = RoleDtoTypeEnum::from($type),
            'color'      => 'color',
            'text_color' => 'textColor',

            'permissions' => fn (object $permissions) => $this->permissions = $this->collectIncluded($permissions, RolePermissionDto::class),
            'users'       => fn (object $users) => $this->users = $this->collectIncluded($users, UserDto::class),
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'         => ['required', 'integer'],
            'name'       => ['required', 'string'],
            'type'       => ['required', Rule::enum(RoleDtoTypeEnum::class)],
            'color'      => ['required', 'string'],
            'text_color' => ['required', 'string'],
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
        return new RoleDtoNotConstructableException($msg, previous: $previous);
    }
}
