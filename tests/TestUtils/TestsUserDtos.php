<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Integer;

trait TestsUserDtos
{
    protected function mockUserApiData(array $override = []): array
    {
        $baseData = [
            'id'                            => Integer::unique(),
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
            'user_groups'                   => ['data' => []],
        ];

        return array_merge($baseData, $override);
    }
}
