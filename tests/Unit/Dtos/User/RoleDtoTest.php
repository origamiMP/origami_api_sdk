<?php

namespace Tests\Unit\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\RolePermissionDto;
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
}
