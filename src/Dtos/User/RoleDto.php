<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\RoleDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RoleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class RoleDto extends ApiResponseDto
{
    use HasAvailableIncludes, HasTimestamps;

    public int $id;

    public string $name;

    public RoleDtoTypeEnum $type;

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
            'type' => fn ($type) => $this->type = RoleDtoTypeEnum::from($type),

            'permissions' => fn ($permissions) => $this->initPermissions($permissions),
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $types = collect(RoleDtoTypeEnum::cases())->pluck('value');

        $rules = [
            'id'   => ['required', 'integer'],
            'name' => ['required', 'string'],
            'type' => ['required', Rule::in($types)],
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
