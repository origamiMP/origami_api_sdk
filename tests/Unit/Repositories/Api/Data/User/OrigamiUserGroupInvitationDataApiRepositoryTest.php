<?php

namespace Tests\Unit\Repositories\Api\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationCheckPendingDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationSendDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupInvitation\UserGroupInvitationResultDtoActionEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\CheckPendingUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetUserGroupInvitationHistoryRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ListUserGroupInvitationsRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ResendUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\SendUserGroupInvitationsRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ValidateUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\User\OrigamiUserGroupInvitationDataApiRepository;
use Tests\TestCase;

class OrigamiUserGroupInvitationDataApiRepositoryTest extends TestCase
{
    /**
     * `POST users/groups/invitations/send` should send the emails and the onboarding url as JSON, and read the
     * result of each invitation.
     */
    public function test_send_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse([
            'data' => [
                $this->mockUserGroupInvitationResultApiData(['email' => 'john@doe.com']),
                $this->mockUserGroupInvitationResultApiData([
                    'email'  => 'jane@doe.com',
                    'action' => UserGroupInvitationResultDtoActionEnum::RESENT->value,
                ]),
            ],
            'message' => 'Invitations processed.',
        ]));
        $paramBag = new SendUserGroupInvitationsRequestParamBag;
        $paramBag->emails = ['john@doe.com', 'jane@doe.com'];
        $paramBag->onboardingUrl = 'https://example.com/onboarding';

        $result = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->send($paramBag);

        $this->assertLastApiRequestWas('POST', 'users/groups/invitations/send');
        $this->assertLastApiRequestJsonWas([
            'emails'         => ['john@doe.com', 'jane@doe.com'],
            'onboarding_url' => 'https://example.com/onboarding',
        ]);
        $this->assertInstanceOf(UserGroupInvitationSendDto::class, $result);
        $this->assertSame('Invitations processed.', $result->message);
        $this->assertSame(
            [UserGroupInvitationResultDtoActionEnum::CREATED, UserGroupInvitationResultDtoActionEnum::RESENT],
            $result->data->pluck('action')->all(),
        );
    }

    public function test_cancel_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['success' => true, 'message' => 'Invitation cancelled.']));

        $result = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->cancel(42);

        $this->assertLastApiRequestWas('DELETE', 'users/groups/invitations/42');
        $this->assertInstanceOf(UserGroupInvitationCancelDto::class, $result);
        $this->assertTrue($result->success);
    }

    /**
     * `POST users/groups/invitations/{id}/resend` should read the resent invitation from the `data` key of the
     * response.
     */
    public function test_resend_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockUserGroupInvitationApiData([
            'id'    => 42,
            'token' => 'new-token',
        ])]));

        $invitation = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->resend(42);

        $this->assertLastApiRequestWas('POST', 'users/groups/invitations/42/resend');
        $this->assertInstanceOf(UserGroupInvitationDto::class, $invitation);
        $this->assertSame(42, $invitation->id);
        $this->assertSame('new-token', $invitation->token);
    }

    /**
     * `POST users/groups/invitations/validate` should send the invitation token and the created user group id as JSON.
     */
    public function test_validate_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['success' => true, 'message' => 'Invitation validated.']));
        $paramBag = new ValidateUserGroupInvitationRequestParamBag;
        $paramBag->token = 'some-token';
        $paramBag->userGroupId = 7;

        $result = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->validate($paramBag);

        $this->assertLastApiRequestWas('POST', 'users/groups/invitations/validate');
        $this->assertLastApiRequestJsonWas(['token' => 'some-token', 'user_group_id' => 7]);
        $this->assertInstanceOf(UserGroupInvitationValidateDto::class, $result);
        $this->assertTrue($result->success);
    }

    /**
     * `GET users/groups/invitations/stats` should read the stats from the `data` key of the response.
     */
    public function test_get_stats_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            'total'           => 10,
            'pending'         => 4,
            'accepted'        => 5,
            'cancelled'       => 1,
            'expired'         => 0,
            'acceptance_rate' => 50.0,
        ]]));

        $stats = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->getStats();

        $this->assertLastApiRequestWas('GET', 'users/groups/invitations/stats');
        $this->assertInstanceOf(UserGroupInvitationStatsDto::class, $stats);
        $this->assertSame(10, $stats->total);
        $this->assertSame(50.0, $stats->acceptanceRate);
    }

    /**
     * `GET users/groups/invitations/history` should send the email as query, and read the list of invitations
     * answered.
     */
    public function test_get_history_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockUserGroupInvitationApiData(['id' => 1]),
            $this->mockUserGroupInvitationApiData(['id' => 2]),
        ]]));
        $paramBag = new GetUserGroupInvitationHistoryRequestParamBag;
        $paramBag->email = 'john@doe.com';

        $history = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->getHistory($paramBag);

        $this->assertLastApiRequestWas('GET', 'users/groups/invitations/history');
        $this->assertLastApiRequestQueryWas(['email' => 'john@doe.com']);
        $this->assertInstanceOf(UserGroupInvitationHistoryDto::class, $history);
        $this->assertSame([1, 2], $history->data->pluck('id')->all());
    }

    /**
     * `GET users/groups/invitations/history` should send the includes available on an invitation as query, and read
     * the included user group of each invitation.
     */
    public function test_get_history_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockUserGroupInvitationApiData(['user_group' => ['data' => $this->mockUserGroupApiData(['id' => 42])]]),
        ]]));
        $paramBag = new GetUserGroupInvitationHistoryRequestParamBag;
        $paramBag->email = 'john@doe.com';
        $paramBag->setIncludes(['user_group', 'unknown']);

        $history = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->getHistory($paramBag);

        $this->assertLastApiRequestWas('GET', 'users/groups/invitations/history');
        $this->assertLastApiRequestQueryWas(['email' => 'john@doe.com', 'include' => ['user_group']]);
        $this->assertInstanceOf(UserGroupInvitationHistoryDto::class, $history);
        $this->assertInstanceOf(UserGroupDto::class, $history->data->first()->userGroup);
        $this->assertSame(42, $history->data->first()->userGroup->id);
    }

    /**
     * `GET users/groups/invitations/check-pending` should send the email as query, and read the result from the
     * `data` key of the response.
     */
    public function test_check_pending_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            'email'                  => 'john@doe.com',
            'has_pending_invitation' => true,
        ]]));
        $paramBag = new CheckPendingUserGroupInvitationRequestParamBag;
        $paramBag->email = 'john@doe.com';

        $result = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->checkPending($paramBag);

        $this->assertLastApiRequestWas('GET', 'users/groups/invitations/check-pending');
        $this->assertLastApiRequestQueryWas(['email' => 'john@doe.com']);
        $this->assertInstanceOf(UserGroupInvitationCheckPendingDto::class, $result);
        $this->assertTrue($result->hasPendingInvitation);
    }

    /**
     * `GET users/groups/invitations` should send the filters, pagination and search params as query, and read the
     * paginated list answered.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockUserGroupInvitationApiData(['id' => 1]),
        ])));
        $paramBag = new ListUserGroupInvitationsRequestParamBag;
        $paramBag->withFilters(['status' => 'pending']);
        $paramBag->withoutPagination();

        $invitations = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'users/groups/invitations');
        $this->assertLastApiRequestQueryWas([
            'filter'             => ['status' => 'pending'],
            'page'               => '1',
            'item_per_page'      => '20',
            'without_pagination' => '1',
        ]);
        $this->assertInstanceOf(UserGroupInvitationListDto::class, $invitations);
        $this->assertSame([1], $invitations->data->pluck('id')->all());
    }

    /**
     * `GET users/groups/invitations` should send the includes available on an invitation as query, and read the
     * included user group of each invitation.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockUserGroupInvitationApiData(['user_group' => ['data' => $this->mockUserGroupApiData(['id' => 42])]]),
        ])));
        $paramBag = new ListUserGroupInvitationsRequestParamBag;
        $paramBag->setIncludes(['user_group', 'unknown']);

        $invitations = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'users/groups/invitations');
        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['user_group'],
        ]);
        $this->assertInstanceOf(UserGroupInvitationListDto::class, $invitations);
        $this->assertInstanceOf(UserGroupDto::class, $invitations->data->first()->userGroup);
        $this->assertSame(42, $invitations->data->first()->userGroup->id);
    }

    /**
     * `POST users/groups/invitations/{id}/resend` should send the includes available on an invitation as query,
     * without any body, and read the included user group.
     */
    public function test_resend_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockUserGroupInvitationApiData([
            'user_group' => ['data' => $this->mockUserGroupApiData(['id' => 42])],
        ])]));
        $paramBag = new ResendUserGroupInvitationRequestParamBag;
        $paramBag->setIncludes(['user_group', 'unknown']);

        $invitation = (new OrigamiUserGroupInvitationDataApiRepository($restClient))->resend(1, $paramBag);

        $this->assertLastApiRequestWas('POST', 'users/groups/invitations/1/resend');
        $this->assertLastApiRequestQueryWas(['include' => ['user_group']]);
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(UserGroupInvitationDto::class, $invitation);
        $this->assertInstanceOf(UserGroupDto::class, $invitation->userGroup);
        $this->assertSame(42, $invitation->userGroup->id);
    }
}
