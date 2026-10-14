<?php

namespace Tests\Unit\Repositories\Client\Oauth;

use Tests\TestCase;

class OrigamiOauthRestClientTest extends TestCase
{
    /**
     * Oauth resources should not be prefixed with `v1/`.
     */
    public function test_url_case_1(): void
    {
        $restClient = $this->mockOauthRestClientReturning($this->jsonResponse([]));

        $restClient->post('oauth/token');

        $this->assertLastApiRequestWas('POST', 'oauth/token', prefix: '');
    }
}
