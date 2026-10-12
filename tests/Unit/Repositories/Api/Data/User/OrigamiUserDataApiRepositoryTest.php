<?php

namespace Tests\Unit\Repositories\Api\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\GetCurrentUserRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\User\UpdateUserRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\User\OrigamiUserDataApiRepository;
use Tests\TestCase;

class OrigamiUserDataApiRepositoryTest extends TestCase
{
    /**
     * `GET me` should read the authenticated user from the `data` key of the response.
     */
    public function test_get_current_authenticated_user_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockUserApiData([
            'id'    => 42,
            'email' => 'jane@doe.com',
        ])]));

        $user = (new OrigamiUserDataApiRepository($restClient))->getCurrentAuthenticatedUser(new GetCurrentUserRequestParamBag);

        $this->assertLastApiRequestWas('GET', 'me');
        $this->assertInstanceOf(UserDto::class, $user);
        $this->assertSame(42, $user->id);
        $this->assertSame('jane@doe.com', $user->email);
    }

    /**
     * `PATCH users/{id}` should send the external configuration as JSON.
     */
    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());
        $paramBag = new UpdateUserRequestParamBag;
        $paramBag->externalConfiguration = '{"theme":"dark"}';

        (new OrigamiUserDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'users/42');
        $this->assertLastApiRequestJsonWas(['external_configuration' => '{"theme":"dark"}']);
    }
}
