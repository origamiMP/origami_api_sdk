<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationsResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationsResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\SendUserGroupInvitationsRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiUserGroupInvitationsApiRepository extends OrigamiDataApiRepository
{
    /**
     * Send invitations to multiple email addresses for seller onboarding
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationsResponseDtoNotConstructableException
     */
    public function sendInvitations(SendUserGroupInvitationsRequestParamBag $paramBag): UserGroupInvitationsResponseDto
    {
        $response = $this->restClient->post('users/groups/invitations/send', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationsResponseDto($responseContent);
    }

    /**
     * Cancel a pending invitation by ID
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws \OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationCancelResponseDtoNotConstructableException
     */
    public function cancelInvitation(int $invitationId): \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationCancelResponseDto
    {
        $response = $this->restClient->delete("users/groups/invitations/{$invitationId}");
        $responseContent = json_decode($response->getBody()->getContents());
        return new \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationCancelResponseDto($responseContent);
    }

    /**
     * Resend an existing invitation with a new token
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws \OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationResendResponseDtoNotConstructableException
     */
    public function resendInvitation(int $invitationId): \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationResendResponseDto
    {
        $response = $this->restClient->post("users/groups/invitations/{$invitationId}/resend");
        $responseContent = json_decode($response->getBody()->getContents())->data;
        return new \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationResendResponseDto($responseContent);
    }

    /**
     * Validate that an invitation was properly accepted when creating the seller account
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws \OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationValidateResponseDtoNotConstructableException
     */
    public function validateInvitation(\OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ValidateUserGroupInvitationRequestParamBag $paramBag): \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationValidateResponseDto
    {
        $response = $this->restClient->post('users/groups/invitations/validate', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());
        return new \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationValidateResponseDto($responseContent);
    }

    /**
     * Retrieve system-wide invitation statistics
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws \OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationStatsResponseDtoNotConstructableException
     */
    public function getStats(): \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationStatsResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations/stats');
        $responseContent = json_decode($response->getBody()->getContents())->data;
        return new \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationStatsResponseDto($responseContent);
    }

    /**
     * Retrieve all invitations sent to a specific email address
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws \OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationHistoryResponseDtoNotConstructableException
     */
    public function getHistory(\OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetUserGroupInvitationHistoryRequestParamBag $paramBag): \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationHistoryResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations/history', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());
        return new \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationHistoryResponseDto($responseContent);
    }

    /**
     * Check if an email address has any pending invitations
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws \OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationCheckPendingResponseDtoNotConstructableException
     */
    public function checkPending(\OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\CheckPendingUserGroupInvitationRequestParamBag $paramBag): \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationCheckPendingResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations/check-pending', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents())->data;
        return new \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationCheckPendingResponseDto($responseContent);
    }

    /**
     * List all invitations with filtering and pagination
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws \OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationListResponseDtoNotConstructableException
     */
    public function getList(\OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetUserGroupInvitationsListRequestParamBag $paramBag): \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationListResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());
        return new \OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationListResponseDto($responseContent);
    }
}
