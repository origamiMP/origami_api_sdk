<?php

namespace Tests\TestUtils;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\Data\OrigamiDataRestClient;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\Guest\OrigamiGuestRestClient;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\Oauth\OrigamiOauthRestClient;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\RestClientRepository;
use Psr\Http\Message\RequestInterface;

/**
 * Drives a real rest client against a fake Origami API (queued Guzzle responses), to test the Repositories
 * without any network call.
 */
trait TestsRestClient
{
    private const TEST_API_BASE_URI = 'http://api.test';

    protected const TEST_API_CLIENT_ID = 'test-client-id';

    protected const TEST_API_CLIENT_SECRET = 'test-client-secret';

    /** @var array<int, array{request: RequestInterface}> */
    protected array $apiRequestHistory = [];

    protected function mockGuestRestClientReturning(Response|\Throwable ...$responses): OrigamiGuestRestClient
    {
        $restClient = new OrigamiGuestRestClient(self::TEST_API_BASE_URI);
        $this->useMockedGuzzleClient($restClient, $responses);

        return $restClient;
    }

    protected function mockDataRestClientReturning(Response|\Throwable ...$responses): OrigamiDataRestClient
    {
        $restClient = new OrigamiDataRestClient(self::TEST_API_BASE_URI, $this->mockOauthTokenDto());
        $this->useMockedGuzzleClient($restClient, $responses);

        return $restClient;
    }

    protected function mockOauthRestClientReturning(Response|\Throwable ...$responses): OrigamiOauthRestClient
    {
        $restClient = new OrigamiOauthRestClient(self::TEST_API_BASE_URI, self::TEST_API_CLIENT_ID, self::TEST_API_CLIENT_SECRET);
        $this->useMockedGuzzleClient($restClient, $responses);

        return $restClient;
    }

    /**
     * A successful Origami API response, with $body encoded as its JSON content.
     */
    protected function jsonResponse(array $body, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($body));
    }

    /**
     * A successful Origami API response without any content (ex: for actions returning nothing).
     */
    protected function emptyResponse(int $status = 204): Response
    {
        return new Response($status);
    }

    protected function lastApiRequest(): RequestInterface
    {
        return end($this->apiRequestHistory)['request'];
    }

    /**
     * $path is the resource path, without $prefix. The default prefix is the one every Guest and Data request has;
     * Oauth requests have none (pass an empty string).
     */
    protected function assertLastApiRequestWas(string $method, string $path, string $prefix = 'v1/'): void
    {
        $this->assertSame($method, $this->lastApiRequest()->getMethod());
        $this->assertSame("/$prefix$path", $this->lastApiRequest()->getUri()->getPath());
    }

    /**
     * Query string values are always strings once sent (ex: an int 2 is received as '2').
     */
    protected function assertLastApiRequestQueryWas(array $expectedQuery): void
    {
        parse_str($this->lastApiRequest()->getUri()->getQuery(), $query);

        $this->assertEquals($expectedQuery, $query);
    }

    protected function assertLastApiRequestJsonWas(array $expectedJson): void
    {
        $this->assertEquals($expectedJson, json_decode((string) $this->lastApiRequest()->getBody(), true));
    }

    protected function assertLastApiRequestHadNoBody(): void
    {
        $this->assertSame('', (string) $this->lastApiRequest()->getBody());
    }

    /**
     * Makes $restClient answer the queued $responses, in order. A \Throwable is thrown instead of answering
     * (ex: a GuzzleHttp\Exception\ConnectException to simulate a network failure).
     *
     * @param  array<Response|\Throwable>  $responses
     */
    protected function useMockedGuzzleClient(RestClientRepository $restClient, array $responses): void
    {
        $this->apiRequestHistory = [];
        $handlerStack = HandlerStack::create(new MockHandler($responses));
        $handlerStack->push(Middleware::history($this->apiRequestHistory));

        $restClient->setGuzzleClient(new GuzzleClient(['handler' => $handlerStack, 'base_uri' => self::TEST_API_BASE_URI]));
    }
}
