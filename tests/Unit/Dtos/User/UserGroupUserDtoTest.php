<?php

namespace Tests\Unit\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\RoleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\RoleDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupUserDtoNotConstructableException;
use Tests\TestCase;

class UserGroupUserDtoTest extends TestCase
{
    /**
     * The `roles` include should be read as a collection of roles, whatever their type: a root role is attached to an
     * operator user group.
     */
    public function test_roles_case_1(): void
    {
        $userGroupUserDto = $this->mockUserGroupUserDto(['roles' => ['data' => [
            $this->mockRoleApiData(['id' => 1, 'type' => RoleDtoTypeEnum::OPERATOR->value]),
            $this->mockRoleApiData(['id' => 2, 'type' => RoleDtoTypeEnum::ROOT->value]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(RoleDto::class, $userGroupUserDto->roles);
        $this->assertSame([1, 2], $userGroupUserDto->roles->pluck('id')->all());
        $this->assertSame([RoleDtoTypeEnum::OPERATOR, RoleDtoTypeEnum::ROOT], $userGroupUserDto->roles->pluck('type')->all());
    }

    /**
     * Without the `roles` include, the roles should stay undefined.
     */
    public function test_roles_case_2(): void
    {
        $userGroupUserDto = $this->mockUserGroupUserDto();

        $this->assertFalse(isset($userGroupUserDto->roles));
    }

    /**
     * An include not wrapped in a `data` key should make the user group user not constructable.
     */
    public function test_roles_case_3(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->mockUserGroupUserDto(['roles' => [$this->mockRoleApiData()]]),
            UserGroupUserDtoNotConstructableException::class,
        );
    }

    /**
     * The `user` include should be read as a user.
     */
    public function test_user_case_1(): void
    {
        $userGroupUserDto = $this->mockUserGroupUserDto(['user' => ['data' => $this->mockUserApiData(['id' => 42])]]);

        $this->assertInstanceOf(UserDto::class, $userGroupUserDto->user);
        $this->assertSame(42, $userGroupUserDto->user->id);
    }

    /**
     * The `user_group` include should be read as a user group.
     */
    public function test_user_group_case_1(): void
    {
        $userGroupUserDto = $this->mockUserGroupUserDto(['user_group' => ['data' => $this->mockUserGroupApiData(['id' => 42])]]);

        $this->assertInstanceOf(UserGroupDto::class, $userGroupUserDto->userGroup);
        $this->assertSame(42, $userGroupUserDto->userGroup->id);
    }

    /**
     * Without the `user_group` include, which the API also leaves out when the user group no longer exists, the user
     * group should stay undefined.
     */
    public function test_user_group_case_2(): void
    {
        $userGroupUserDto = $this->mockUserGroupUserDto();

        $this->assertFalse(isset($userGroupUserDto->userGroup));
    }
}
