<?php

namespace Tests\Unit\Repositories\Client\Data;

use OrigamiMp\OrigamiApiSdk\Repositories\Client\Data\OrigamiDataRestClient;
use Tests\TestCase;

class OrigamiDataRestClientTest extends TestCase
{
    /**
     * Every request should be authenticated with the oauth token, without any user group context by default.
     */
    public function test_headers_case_1(): void
    {
        $restClient = new OrigamiDataRestClient('http://api.test', $this->mockOauthTokenDto(['access_token' => 'some-access-token']));
        $this->useMockedGuzzleClient($restClient, [$this->jsonResponse([])]);

        $restClient->get('some_resource');

        $this->assertSame('Bearer some-access-token', $this->lastApiRequest()->getHeaderLine('Authorization'));
        $this->assertFalse($this->lastApiRequest()->hasHeader('context'));
    }

    /**
     * With a user group id, every request should be made in the context of this user group.
     */
    public function test_headers_case_2(): void
    {
        $restClient = new OrigamiDataRestClient('http://api.test', $this->mockOauthTokenDto(), 42);
        $this->useMockedGuzzleClient($restClient, [$this->jsonResponse([])]);

        $restClient->get('some_resource');

        $this->assertSame('42', $this->lastApiRequest()->getHeaderLine('context'));
    }
}
