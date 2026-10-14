<?php

namespace Tests\Unit\Repositories\Api\Guest\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\User\AcceptUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\User\OrigamiUserGroupInvitationGuestApiRepository;
use Tests\TestCase;

class OrigamiUserGroupInvitationGuestApiRepositoryTest extends TestCase
{
    /**
     * `POST users/groups/invitations/accept` should send the invitation token as JSON, and read the accepted
     * invitation from the `data` key of the response.
     */
    public function test_accept_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse(['data' => $this->mockUserGroupInvitationApiData([
            'id'     => 42,
            'status' => UserGroupInvitationDtoStatusEnum::ACCEPTED->value,
        ])]));
        $paramBag = new AcceptUserGroupInvitationRequestParamBag;
        $paramBag->token = 'some-token';

        $invitation = (new OrigamiUserGroupInvitationGuestApiRepository($restClient))->accept($paramBag);

        $this->assertLastApiRequestWas('POST', 'users/groups/invitations/accept');
        $this->assertLastApiRequestJsonWas(['token' => 'some-token']);
        $this->assertInstanceOf(UserGroupInvitationDto::class, $invitation);
        $this->assertSame(42, $invitation->id);
        $this->assertSame(UserGroupInvitationDtoStatusEnum::ACCEPTED, $invitation->status);
    }
}
