<?php

namespace Tests\Unit\Repositories\Api\Oauth;

use OrigamiMp\OrigamiApiSdk\Dtos\Oauth\OauthTokenDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Oauth\GetOauthTokenWithPasswordRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Oauth\GetOauthTokenWithRefreshTokenRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Oauth\OrigamiOauthApiRepository;
use Tests\TestCase;

class OrigamiOauthApiRepositoryTest extends TestCase
{
    /**
     * `POST oauth/token` (no `v1/` prefix) with a password grant: the client id and secret not set on the param bag
     * should be the ones of the Oauth rest client.
     */
    public function test_get_oauth_token_case_1(): void
    {
        $restClient = $this->mockOauthRestClientReturning($this->jsonResponse($this->mockOauthTokenApiData([
            'access_token'  => 'new-access-token',
            'refresh_token' => 'new-refresh-token',
        ])));
        $paramBag = new GetOauthTokenWithPasswordRequestParamBag;
        $paramBag->username = 'john@doe.com';
        $paramBag->password = 'password';

        $oauthToken = (new OrigamiOauthApiRepository($restClient))->getOauthToken($paramBag);

        $this->assertLastApiRequestWas('POST', 'oauth/token', prefix: '');
        $this->assertLastApiRequestJsonWas([
            'client_id'     => self::TEST_API_CLIENT_ID,
            'client_secret' => self::TEST_API_CLIENT_SECRET,
            'grant_type'    => 'password',
            'username'      => 'john@doe.com',
            'password'      => 'password',
        ]);
        $this->assertInstanceOf(OauthTokenDto::class, $oauthToken);
        $this->assertSame('new-access-token', $oauthToken->getAccessToken());
        $this->assertSame('new-refresh-token', $oauthToken->getRefreshToken());
    }

    /**
     * `POST oauth/token` with a refresh token grant: the client id and secret explicitly set on the param bag
     * should be kept over the ones of the Oauth rest client.
     */
    public function test_get_oauth_token_case_2(): void
    {
        $restClient = $this->mockOauthRestClientReturning($this->jsonResponse($this->mockOauthTokenApiData()));
        $paramBag = new GetOauthTokenWithRefreshTokenRequestParamBag;
        $paramBag->clientId = 'other-client-id';
        $paramBag->clientSecret = 'other-client-secret';
        $paramBag->refreshToken = 'some-refresh-token';

        (new OrigamiOauthApiRepository($restClient))->getOauthToken($paramBag);

        $this->assertLastApiRequestJsonWas([
            'client_id'     => 'other-client-id',
            'client_secret' => 'other-client-secret',
            'grant_type'    => 'refresh_token',
            'refresh_token' => 'some-refresh-token',
        ]);
    }
}
