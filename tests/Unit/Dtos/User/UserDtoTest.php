<?php

namespace Tests\Unit\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\RoleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserDtoNotConstructableException;
use Tests\TestCase;

class UserDtoTest extends TestCase
{
    /**
     * A user created without first name and last name should be readable: the API sends empty strings,
     * and a name made of a single space.
     */
    public function test_names_case_1(): void
    {
        $userDto = $this->mockUserDto(['firstname' => '', 'lastname' => '', 'name' => ' ']);

        $this->assertSame('', $userDto->firstName);
        $this->assertSame('', $userDto->lastName);
        $this->assertSame(' ', $userDto->name);
    }

    /**
     * The `user_groups` include should be read as a collection of user groups.
     */
    public function test_user_groups_case_1(): void
    {
        $userDto = $this->mockUserDto(['user_groups' => ['data' => [
            $this->mockUserGroupApiData(['id' => 1]),
            $this->mockUserGroupApiData(['id' => 2]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(UserGroupDto::class, $userDto->userGroups);
        $this->assertSame([1, 2], $userDto->userGroups->pluck('id')->all());
    }

    /**
     * The `roles` include should be read as a collection of roles.
     */
    public function test_roles_case_1(): void
    {
        $userDto = $this->mockUserDto(['roles' => ['data' => [
            $this->mockRoleApiData(['id' => 1]),
            $this->mockRoleApiData(['id' => 2]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(RoleDto::class, $userDto->roles);
        $this->assertSame([1, 2], $userDto->roles->pluck('id')->all());
    }

    /**
     * Without the `roles` include, the roles should stay undefined.
     */
    public function test_roles_case_2(): void
    {
        $userDto = $this->mockUserDto();

        $this->assertFalse(isset($userDto->roles));
    }

    /**
     * An include not wrapped in a `data` key should make the user not constructable.
     */
    public function test_roles_case_3(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->mockUserDto(['roles' => [$this->mockRoleApiData()]]),
            UserDtoNotConstructableException::class,
        );
    }
}
