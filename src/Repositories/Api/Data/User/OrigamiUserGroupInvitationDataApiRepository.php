<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationCheckPendingDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationSendDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCheckPendingResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationListResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationsSendResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\CheckPendingUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetUserGroupInvitationHistoryRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ListUserGroupInvitationsRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\SendUserGroupInvitationsRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ValidateUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiUserGroupInvitationDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Send invitations to multiple email addresses for seller onboarding
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationsSendResponseDtoNotConstructableException
     */
    public function send(SendUserGroupInvitationsRequestParamBag $paramBag): UserGroupInvitationSendDto
    {
        $response = $this->restClient->post('users/groups/invitations/send', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationSendDto($responseContent);
    }

    /**
     * Cancel a pending invitation by ID
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationCancelResponseDtoNotConstructableException
     */
    public function cancel(int $invitationId): UserGroupInvitationCancelDto
    {
        $response = $this->restClient->delete("users/groups/invitations/{$invitationId}");
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationCancelDto($responseContent);
    }

    /**
     * Resend an existing invitation with a new token
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationDtoNotConstructableException
     */
    public function resend(int $invitationId): UserGroupInvitationDto
    {
        $response = $this->restClient->post("users/groups/invitations/{$invitationId}/resend");
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Validate that an invitation was properly accepted when creating the seller account
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationValidateResponseDtoNotConstructableException
     */
    public function validate(ValidateUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationValidateDto
    {
        $response = $this->restClient->post('users/groups/invitations/validate', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationValidateDto($responseContent);
    }

    /**
     * Retrieve system-wide invitation statistics
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationStatsResponseDtoNotConstructableException
     */
    public function getStats(): UserGroupInvitationStatsDto
    {
        $response = $this->restClient->get('users/groups/invitations/stats');
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationStatsDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Retrieve all invitations sent to a specific email address
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationHistoryResponseDtoNotConstructableException
     */
    public function getHistory(GetUserGroupInvitationHistoryRequestParamBag $paramBag): UserGroupInvitationHistoryDto
    {
        $response = $this->restClient->get('users/groups/invitations/history', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationHistoryDto($responseContent);
    }

    /**
     * Check if an email address has any pending invitations
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationCheckPendingResponseDtoNotConstructableException
     */
    public function checkPending(CheckPendingUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationCheckPendingDto
    {
        $response = $this->restClient->get('users/groups/invitations/check-pending', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationCheckPendingDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * List all invitations with filtering and pagination
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationListResponseDtoNotConstructableException
     */
    public function list(ListUserGroupInvitationsRequestParamBag $paramBag): UserGroupInvitationListDto
    {
        $response = $this->restClient->get('users/groups/invitations', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new UserGroupInvitationListDto($responseContent);
    }
}
