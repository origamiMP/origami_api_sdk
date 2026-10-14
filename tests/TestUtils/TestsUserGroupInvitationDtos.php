<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupInvitation\UserGroupInvitationResultDtoActionEnum;
use Tests\TestCase;

trait TestsUserGroupInvitationDtos
{
    protected function mockUserGroupInvitationApiData(array $override = []): array
    {
        $baseData = [
            'id'             => TestCase::unique(),
            'user_group_id'  => null,
            'email'          => 'john@doe.com',
            'status'         => UserGroupInvitationDtoStatusEnum::PENDING->value,
            'onboarding_url' => 'https://example.com/onboarding',
            'sent_at'        => null,
            'accepted_at'    => null,
            'expires_at'     => '2024-05-19 16:12:56',
            'is_expired'     => false,
            'is_valid'       => true,
            'token'          => 'some-token',
            'created_at'     => '2024-05-12 16:12:56',
            'updated_at'     => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }

    protected function mockUserGroupInvitationResultApiData(array $override = []): array
    {
        $baseData = [
            'email'         => 'john@doe.com',
            'success'       => true,
            'invitation_id' => TestCase::unique(),
            'action'        => UserGroupInvitationResultDtoActionEnum::CREATED->value,
            'error'         => null,
        ];

        return array_merge($baseData, $override);
    }
}
