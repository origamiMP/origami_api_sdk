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
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCheckPendingDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationSendDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\CheckPendingUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetUserGroupInvitationHistoryRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ListUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ResendUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\SendUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\ValidateUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiUserGroupInvitationDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationListDtoNotConstructableException
     */
    public function list(?ListUserGroupInvitationRequestParamBag $paramBag = null): UserGroupInvitationListDto
    {
        $response = $this->restClient->get('users/groups/invitations', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationListDto($responseContent);
    }

    /**
     * Send invitations to multiple email addresses for seller onboarding
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationSendDtoNotConstructableException
     */
    public function send(SendUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationSendDto
    {
        $response = $this->restClient->post('users/groups/invitations/send', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationSendDto($responseContent);
    }

    /**
     * Cancel a pending invitation
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationCancelDtoNotConstructableException
     */
    public function cancel(int $id): UserGroupInvitationCancelDto
    {
        $response = $this->restClient->delete("users/groups/invitations/{$id}");
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationCancelDto($responseContent);
    }

    /**
     * Resend an existing invitation with a new token
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws UserGroupInvitationDtoNotConstructableException
     */
    public function resend(int $id, ?ResendUserGroupInvitationRequestParamBag $paramBag = null): UserGroupInvitationDto
    {
        $response = $this->restClient->post("users/groups/invitations/{$id}/resend", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationDto($this->getResponseContentData($responseContent));
    }

    /**
     * Validate that an invitation was properly accepted when creating the seller account
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationValidateDtoNotConstructableException
     */
    public function validate(ValidateUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationValidateDto
    {
        $response = $this->restClient->post('users/groups/invitations/validate', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationValidateDto($responseContent);
    }

    /**
     * Retrieve system-wide invitation statistics
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws UserGroupInvitationStatsDtoNotConstructableException
     */
    public function getStats(): UserGroupInvitationStatsDto
    {
        $response = $this->restClient->get('users/groups/invitations/stats');
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationStatsDto($this->getResponseContentData($responseContent));
    }

    /**
     * Retrieve all invitations sent to a specific email address
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationHistoryDtoNotConstructableException
     */
    public function getHistory(GetUserGroupInvitationHistoryRequestParamBag $paramBag): UserGroupInvitationHistoryDto
    {
        $response = $this->restClient->get('users/groups/invitations/history', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationHistoryDto($responseContent);
    }

    /**
     * Check if an email address has any pending invitations
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws UserGroupInvitationCheckPendingDtoNotConstructableException
     */
    public function checkPending(CheckPendingUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationCheckPendingDto
    {
        $response = $this->restClient->get('users/groups/invitations/check-pending', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new UserGroupInvitationCheckPendingDto($this->getResponseContentData($responseContent));
    }
}
