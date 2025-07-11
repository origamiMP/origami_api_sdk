<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitationAcceptResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitationAcceptResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\User\AcceptUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiGuestUserGroupInvitationsApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Accept an invitation using the token from the email (public endpoint)
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationAcceptResponseDtoNotConstructableException
     */
    public function acceptInvitation(AcceptUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationAcceptResponseDto
    {
        $response = $this->restClient->post('users/groups/invitations/accept', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents())->data;

        return new UserGroupInvitationAcceptResponseDto($responseContent);
    }
}
