<?php

namespace Tests\Unit\Repositories\Api\Data\User;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleDto;
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
     * `GET me` should send the includes available on a user as query, and read the included roles.
     */
    public function test_get_current_authenticated_user_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockUserApiData([
            'roles' => ['data' => [$this->mockRoleApiData(['id' => 42])]],
        ])]));
        $paramBag = new GetCurrentUserRequestParamBag;
        $paramBag->setIncludes(['roles', 'unknown', 'user_groups']);

        $user = (new OrigamiUserDataApiRepository($restClient))->getCurrentAuthenticatedUser($paramBag);

        $this->assertLastApiRequestWas('GET', 'me');
        $this->assertLastApiRequestQueryWas(['include' => ['roles', 'user_groups']]);
        $this->assertInstanceOf(UserDto::class, $user);
        $this->assertSame([42], $user->roles->pluck('id')->all());
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

    /**
     * `GET me` should send the includes available on a user as query (the commented ones being dropped), and read the
     * included module.
     */
    public function test_get_current_authenticated_user_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockUserApiData([
            'module' => ['data' => $this->mockModuleApiData(['id' => 42])],
        ])]));
        $paramBag = new GetCurrentUserRequestParamBag;
        $paramBag->setIncludes(['module', 'user_group_count']);

        $user = (new OrigamiUserDataApiRepository($restClient))->getCurrentAuthenticatedUser($paramBag);

        $this->assertLastApiRequestWas('GET', 'me');
        $this->assertLastApiRequestQueryWas(['include' => ['module']]);
        $this->assertInstanceOf(UserDto::class, $user);
        $this->assertInstanceOf(ModuleDto::class, $user->module);
        $this->assertSame(42, $user->module->id);
    }
}
