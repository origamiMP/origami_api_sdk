<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Password;

use OrigamiMp\OrigamiApiSdk\Dtos\Password\ResetPasswordDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Password\SendResetPasswordEmailDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\ResetPasswordDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\SendResetPasswordEmailDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password\ResetPasswordRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password\SendResetPasswordEmailRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiPasswordGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws SendResetPasswordEmailDtoNotConstructableException
     */
    public function sendResetPasswordEmail(SendResetPasswordEmailRequestParamBag $requestParamBag): SendResetPasswordEmailDto
    {
        $response = $this->restClient->post('password/email', $requestParamBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new SendResetPasswordEmailDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ResetPasswordDtoNotConstructableException
     */
    public function resetPassword(ResetPasswordRequestParamBag $requestParamBag): ResetPasswordDto
    {
        $response = $this->restClient->post('password/reset', $requestParamBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new ResetPasswordDto($responseContent);
    }
}
