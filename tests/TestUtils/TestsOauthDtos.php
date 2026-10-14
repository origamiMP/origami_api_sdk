<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Oauth\OauthTokenDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;

trait TestsOauthDtos
{
    protected function mockOauthTokenApiData(array $override = []): array
    {
        $baseData = [
            'token_type'    => 'Bearer',
            'access_token'  => 'some-access-token',
            'refresh_token' => 'some-refresh-token',
            'expires_in'    => 3600,
        ];

        return array_merge($baseData, $override);
    }

    protected function mockOauthTokenDto(array $override = []): OauthTokenDto
    {
        return new OauthTokenDto(Obj::fromArray($this->mockOauthTokenApiData($override)));
    }
}
