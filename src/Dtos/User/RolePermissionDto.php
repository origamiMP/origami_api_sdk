<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RolePermissionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class RolePermissionDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    /**
     * Permission key, for example "users.update", "users.all", or a functional role such as "catalog".
     */
    public string $permission;

    /**
     * Permissions implied by this one, resolved by the Origami API from its functional roles configuration.
     *
     * @var string[]
     */
    public array $associatedPermissions;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                     => 'id',
            'permission'             => 'permission',
            'associated_permissions' => 'associatedPermissions',
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
            'permission'               => ['required', 'string'],
            'associated_permissions'   => ['present', 'array'],
            'associated_permissions.*' => ['string'],
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
        return new RolePermissionDtoNotConstructableException($msg, previous: $previous);
    }
}
