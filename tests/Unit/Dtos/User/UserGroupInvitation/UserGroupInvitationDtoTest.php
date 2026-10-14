<?php

namespace Tests\Unit\Dtos\User\UserGroupInvitation;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoNotConstructableException;
use Tests\TestCase;

class UserGroupInvitationDtoTest extends TestCase
{
    /**
     * The `user_group` include should be read as a single user group.
     */
    public function test_user_group_case_1(): void
    {
        $invitationDto = $this->mockUserGroupInvitationDto([
            'user_group_id' => 42,
            'user_group'    => ['data' => $this->mockUserGroupApiData(['id' => 42])],
        ]);

        $this->assertInstanceOf(UserGroupDto::class, $invitationDto->userGroup);
        $this->assertSame(42, $invitationDto->userGroup->id);
    }

    /**
     * A `user_group` include not wrapped in a `data` key should make the invitation not constructable.
     */
    public function test_user_group_case_2(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->mockUserGroupInvitationDto(['user_group' => $this->mockUserGroupApiData()]),
            UserGroupInvitationDtoNotConstructableException::class,
        );
    }
}
