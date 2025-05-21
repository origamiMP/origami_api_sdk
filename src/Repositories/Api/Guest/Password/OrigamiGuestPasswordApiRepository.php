<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Password;

use OrigamiMp\OrigamiApiSdk\Dtos\Password\SendResetPasswordEmailDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password\SendResetPasswordEmailRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password\ResetPasswordRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;
use OrigamiMp\OrigamiApiSdk\Dtos\Password\ResetPasswordDto;

class OrigamiGuestPasswordApiRepository extends OrigamiGuestApiRepository
{
    public function sendResetPasswordEmail(SendResetPasswordEmailRequestParamBag $requestParamBag): SendResetPasswordEmailDto
    {
        $response = $this->restClient->post('password/email', $requestParamBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new SendResetPasswordEmailDto($responseContent);
    }

    public function resetPassword(ResetPasswordRequestParamBag $requestParamBag): ResetPasswordDto
    {
        $response = $this->restClient->post('password/reset', $requestParamBag);

        $responseContent = json_decode($response->getBody()->getContents());

        return new ResetPasswordDto($responseContent);
    }
}