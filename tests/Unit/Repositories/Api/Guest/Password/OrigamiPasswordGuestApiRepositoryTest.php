<?php

namespace Tests\Unit\Repositories\Api\Guest\Password;

use OrigamiMp\OrigamiApiSdk\Dtos\Password\ResetPasswordDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Password\SendResetPasswordEmailDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password\ResetPasswordRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Password\SendResetPasswordEmailRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Password\OrigamiPasswordGuestApiRepository;
use Tests\TestCase;

class OrigamiPasswordGuestApiRepositoryTest extends TestCase
{
    /**
     * `POST password/email` should send the email and the custom reset link as JSON.
     */
    public function test_send_reset_password_email_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse(['success' => true]));
        $paramBag = new SendResetPasswordEmailRequestParamBag;
        $paramBag->email = 'john@doe.com';
        $paramBag->resetLink = 'https://example.com/reset';

        $result = (new OrigamiPasswordGuestApiRepository($restClient))->sendResetPasswordEmail($paramBag);

        $this->assertLastApiRequestWas('POST', 'password/email');
        $this->assertLastApiRequestJsonWas(['email' => 'john@doe.com', 'reset_link' => 'https://example.com/reset']);
        $this->assertInstanceOf(SendResetPasswordEmailDto::class, $result);
        $this->assertTrue($result->success);
    }

    /**
     * `POST password/reset` should send the email, the new password (with its confirmation) and the reset token as JSON.
     */
    public function test_reset_password_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse(['success' => true]));
        $paramBag = new ResetPasswordRequestParamBag;
        $paramBag->email = 'john@doe.com';
        $paramBag->password = 'new-password';
        $paramBag->passwordConfirmation = 'new-password';
        $paramBag->token = 'some-token';

        $result = (new OrigamiPasswordGuestApiRepository($restClient))->resetPassword($paramBag);

        $this->assertLastApiRequestWas('POST', 'password/reset');
        $this->assertLastApiRequestJsonWas([
            'email'                 => 'john@doe.com',
            'password'              => 'new-password',
            'password_confirmation' => 'new-password',
            'token'                 => 'some-token',
        ]);
        $this->assertInstanceOf(ResetPasswordDto::class, $result);
        $this->assertTrue($result->success);
    }
}
