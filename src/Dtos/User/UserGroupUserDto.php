<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupUserDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

/**
 * Membership of a User in a UserGroup.
 */
class UserGroupUserDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public int $userId;

    public int $userGroupId;

    /**
     * Null when the membership was attached without timestamps (memberships created by the SSO modules).
     */
    public ?Carbon $createdAt;

    /**
     * Null when the membership was attached without timestamps (memberships created by the SSO modules).
     */
    public ?Carbon $updatedAt;

    /**
     * User of this membership.
     *
     * May be undefined if the corresponding data was not included.
     */
    public UserDto $user;

    /**
     * Roles of the User in this UserGroup, whatever their type (a root role is attached to an operator UserGroup).
     *
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, RoleDto>
     */
    public Collection $roles;

    /**
     * UserGroup of this membership.
     *
     * May be undefined if the corresponding data was not included, or if the UserGroup no longer exists.
     */
    public UserGroupDto $userGroup;

    public static function getAvailableIncludes(): array
    {
        return [
            'user'       => UserDto::class,
            'roles'      => RoleDto::class,
            'user_group' => UserGroupDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'            => 'id',
            'user_id'       => 'userId',
            'user_group_id' => 'userGroupId',
            'created_at'    => fn ($createdAt) => $this->createdAt = is_null($createdAt) ? null : Carbon::parse($createdAt),
            'updated_at'    => fn ($updatedAt) => $this->updatedAt = is_null($updatedAt) ? null : Carbon::parse($updatedAt),

            'user'       => fn (object $user) => $this->user = $this->makeIncluded($user, UserDto::class),
            'roles'      => fn (object $roles) => $this->roles = $this->collectIncluded($roles, RoleDto::class),
            'user_group' => fn (object $userGroup) => $this->userGroup = $this->makeIncluded($userGroup, UserGroupDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupUserDtoNotConstructableException($msg, previous: $previous);
    }
}
