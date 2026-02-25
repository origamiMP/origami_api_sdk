<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\User\AcceptUserGroupInvitationRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiUserGroupInvitationGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Accept an invitation using the token from the email (public endpoint)
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserGroupInvitationDtoNotConstructableException
     */
    public function accept(AcceptUserGroupInvitationRequestParamBag $paramBag): UserGroupInvitationDto
    {
        $response = $this->restClient->post('users/groups/invitations/accept', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents())->data;

        return new UserGroupInvitationDto($responseContent);
    }
}
