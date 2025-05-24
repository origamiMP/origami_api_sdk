<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetCurrentUserRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiUserDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws UserDtoNotConstructableException
     */
    public function getCurrentAuthenticatedUser(GetCurrentUserRequestParamBag $paramBag): UserDto
    {
        $response = $this->restClient->get('me', $paramBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new UserDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }
}
