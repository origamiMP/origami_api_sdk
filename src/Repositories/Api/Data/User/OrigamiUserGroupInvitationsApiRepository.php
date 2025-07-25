<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationCancelResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationCheckPendingResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationHistoryResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationListResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationSendResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationStatsResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationValidateResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationCancelResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationCheckPendingResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationHistoryResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationListResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationsSendResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationStatsResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationValidateResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\CheckPendingUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetUserGroupInvitationHistoryRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetUserGroupInvitationsListRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\SendUserGroupInvitationsRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ValidateUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiUserGroupInvitationsApiRepository extends OrigamiDataApiRepository
{
    /**
     * Send invitations to multiple email addresses for seller onboarding
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationsSendResponseDtoNotConstructableException
     */
    public function sendInvitations(SendUserGroupInvitationsRequestParamBag $paramBag): UserGroupInvitationSendResponseDto
    {
        $response = $this->restClient->post('users/groups/invitations/send', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationSendResponseDto($responseContent);
    }

    /**
     * Cancel a pending invitation by ID
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationCancelResponseDtoNotConstructableException
     */
    public function cancelInvitation(int $invitationId): UserGroupInvitationCancelResponseDto
    {
        $response = $this->restClient->delete("users/groups/invitations/{$invitationId}");
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationCancelResponseDto($responseContent);
    }

    /**
     * Resend an existing invitation with a new token
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationDtoNotConstructableException
     */
    public function resendInvitation(int $invitationId): UserGroupInvitationDto
    {
        $response = $this->restClient->post("users/groups/invitations/{$invitationId}/resend");
        $responseContent = json_decode($response->getBody()->getContents())->data;

        return new UserGroupInvitationDto($responseContent);
    }

    /**
     * Validate that an invitation was properly accepted when creating the seller account
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationValidateResponseDtoNotConstructableException
     */
    public function validateInvitation(ValidateUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationValidateResponseDto
    {
        $response = $this->restClient->post('users/groups/invitations/validate', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationValidateResponseDto($responseContent);
    }

    /**
     * Retrieve system-wide invitation statistics
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationStatsResponseDtoNotConstructableException
     */
    public function getStats(): UserGroupInvitationStatsResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations/stats');
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationStatsResponseDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Retrieve all invitations sent to a specific email address
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationHistoryResponseDtoNotConstructableException
     */
    public function getHistory(GetUserGroupInvitationHistoryRequestParamBag $paramBag): UserGroupInvitationHistoryResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations/history', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationHistoryResponseDto($responseContent);
    }

    // TODO Onboarding seller : review
    /**
     * Check if an email address has any pending invitations
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationCheckPendingResponseDtoNotConstructableException
     */
    public function checkPending(CheckPendingUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationCheckPendingResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations/check-pending', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents())->data;

        return new UserGroupInvitationCheckPendingResponseDto($responseContent);
    }

    /**
     * List all invitations with filtering and pagination
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationListResponseDtoNotConstructableException
     */
    public function getList(GetUserGroupInvitationsListRequestParamBag $paramBag): UserGroupInvitationListResponseDto
    {
        $response = $this->restClient->get('users/groups/invitations', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationListResponseDto($responseContent);
    }
}
