<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\User\RoleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\RoleDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsUserDtos
{
    protected function mockUserApiData(array $override = []): array
    {
        $baseData = [
            'id'                            => TestCase::unique(),
            'firstname'                     => 'John',
            'lastname'                      => 'Doe',
            'name'                          => 'John Doe',
            'email'                         => 'john@doe.com',
            'external_configuration'        => null,
            'language_id'                   => null,
            'language_iso'                  => null,
            'module_id'                     => null,
            'has_access_to_seller_features' => true,
            'state'                         => UserDtoStateEnum::ENABLED->value,
            'is_account_validated'          => true,
            'created_at'                    => '2024-05-12 16:12:56',
            'updated_at'                    => '2024-05-12 16:12:56',
            'additional_information'        => [],
            'user_groups'                   => ['data' => []],
        ];

        return [...$baseData, ...$override];
    }

    protected function mockUserDto(array $override = []): UserDto
    {
        return new UserDto(Obj::fromArray($this->mockUserApiData($override)));
    }

    protected function mockUserGroupApiData(array $override = []): array
    {
        $baseData = [
            'id'                   => TestCase::unique(),
            'type'                 => UserGroupDtoTypeEnum::SELLER->value,
            'user_group_parent_id' => TestCase::unique(),
            'firstname'            => 'John',
            'lastname'             => 'Doe',
            'name'                 => 'John Doe',
            'email'                => 'john@doe.com',
            'phone'                => null,
            'phone_area_code'      => null,
            'address_id'           => null,
            'address_line1'        => '',
            'address_line2'        => '',
            'address_city'         => '',
            'address_postcode'     => '',
            'address_country'      => '',
            'address_country_iso'  => '',
            'address_longitude'    => '',
            'address_latitude'     => '',
            'address_region'       => '',
            'address_full'         => '',
            'image_url'            => null,
            'image_cover_url'      => null,
            'rate_total'           => 0,
            'rate_count'           => 0,
            'language_iso'         => null,
            'language_id'          => null,
            'external_uid'         => null,
            'email_validated'      => false,
            'phone_validated'      => false,
            'created_at'           => '2024-05-12 16:12:56',
            'updated_at'           => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockUserGroupDto(array $override = []): UserGroupDto
    {
        return new UserGroupDto(Obj::fromArray($this->mockUserGroupApiData($override)));
    }

    protected function mockRoleApiData(array $override = []): array
    {
        $baseData = [
            'id'         => TestCase::unique(),
            'name'       => 'Administrator',
            'type'       => RoleDtoTypeEnum::SELLER->value,
            'color'      => '#000000',
            'text_color' => '#FFFFFF',
            'created_at' => '2024-05-12 16:12:56',
            'updated_at' => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockRoleDto(array $override = []): RoleDto
    {
        return new RoleDto(Obj::fromArray($this->mockRoleApiData($override)));
    }

    protected function mockRolePermissionApiData(array $override = []): array
    {
        $baseData = [
            'id'                     => TestCase::unique(),
            'permission'             => 'products.read',
            'associated_permissions' => [],
        ];

        return [...$baseData, ...$override];
    }
}
