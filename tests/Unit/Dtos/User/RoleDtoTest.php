<?php

namespace Tests\Unit\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\RolePermissionDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use Tests\TestCase;

class RoleDtoTest extends TestCase
{
    /**
     * The `permissions` include should be read as a collection of permissions.
     */
    public function test_permissions_case_1(): void
    {
        $roleDto = $this->mockRoleDto(['permissions' => ['data' => [
            $this->mockRolePermissionApiData(['permission' => 'products.read']),
            $this->mockRolePermissionApiData(['permission' => 'orders.read', 'associated_permissions' => ['orders.export']]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(RolePermissionDto::class, $roleDto->permissions);
        $this->assertSame(['products.read', 'orders.read'], $roleDto->permissions->pluck('permission')->all());
        $this->assertSame(['orders.export'], $roleDto->permissions[1]->associatedPermissions);
    }

    /**
     * The `users` include should be read as a collection of users.
     */
    public function test_users_case_1(): void
    {
        $roleDto = $this->mockRoleDto(['users' => ['data' => [
            $this->mockUserApiData(['id' => 1]),
            $this->mockUserApiData(['id' => 2]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(UserDto::class, $roleDto->users);
        $this->assertSame([1, 2], $roleDto->users->pluck('id')->all());
    }
}
