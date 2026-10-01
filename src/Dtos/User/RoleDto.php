<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RoleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class RoleDto extends ApiResponseDto
{
    use HasAvailableIncludes, HasTimestamps;

    public int $id;

    public string $name;

    public string $type;

    /**
     * Permissions granted by this Role.
     *
     * May be undefined if the corresponding data was not included.
     *
     * @var RolePermissionDto[]|Collection
     */
    public Collection $permissions;

    public static function getAvailableIncludes(): array
    {
        return [
            'permissions' => RolePermissionDto::class,
            // 'users',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'   => 'id',
            'name' => 'name',
            'type' => 'type',

            'permissions' => fn ($permissions) => $this->initPermissions($permissions),
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'   => ['required', 'integer'],
            'name' => ['required', 'string'],
            'type' => ['required', 'string'],
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
        return new RoleDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initPermissions($permissions): void
    {
        $this->throwIfDataFieldOnObjectIsEmpty($permissions);

        $this->permissions = collect($permissions->data)->map(fn ($permission) => new RolePermissionDto($permission));
    }
}
