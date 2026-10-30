<?php

namespace Tests\Unit\Repositories\Api\Guest\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
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

    /**
     * `POST users/groups/invitations/accept` should send the includes available on an invitation as query, along
     * with the token as JSON, and read the included user group.
     */
    public function test_accept_case_2(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse(['data' => $this->mockUserGroupInvitationApiData([
            'user_group' => ['data' => $this->mockUserGroupApiData(['id' => 42])],
        ])]));
        $paramBag = new AcceptUserGroupInvitationRequestParamBag;
        $paramBag->token = 'some-token';
        $paramBag->setIncludes(['user_group', 'unknown']);

        $invitation = (new OrigamiUserGroupInvitationGuestApiRepository($restClient))->accept($paramBag);

        $this->assertLastApiRequestWas('POST', 'users/groups/invitations/accept');
        $this->assertLastApiRequestQueryWas(['include' => ['user_group']]);
        $this->assertLastApiRequestJsonWas(['token' => 'some-token']);
        $this->assertInstanceOf(UserGroupInvitationDto::class, $invitation);
        $this->assertInstanceOf(UserGroupDto::class, $invitation->userGroup);
        $this->assertSame(42, $invitation->userGroup->id);
    }

    /**
     * `POST users/groups/invitations/accept` answering without a `data` key should be thrown as a response without
     * data.
     */
    public function test_accept_case_3(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse([]));
        $paramBag = new AcceptUserGroupInvitationRequestParamBag;
        $paramBag->token = 'some-token';

        $this->assertThatExceptionWasThrown(
            fn () => (new OrigamiUserGroupInvitationGuestApiRepository($restClient))->accept($paramBag),
            OrigamiApiResponseWithoutDataException::class,
        );
    }
}
