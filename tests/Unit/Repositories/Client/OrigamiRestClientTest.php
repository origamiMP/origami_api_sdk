<?php

namespace Tests\Unit\Repositories\Client;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use OrigamiMp\OrigamiApiSdk\Enums\Error\OrigamiApiErrorCodeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiBadRequestException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiClientErrorException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiConflictException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiForbiddenException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiMultipleException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiNotFoundException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiSingleException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiTooManyRequestsException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnauthorizedException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnprocessableEntityException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Helpers\OrigamiApiSdk;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\Guest\OrigamiGuestRestClient;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\OrigamiRestClient;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Behavior shared by every Origami rest client, tested through the Guest one (which adds nothing to it).
 */
class OrigamiRestClientTest extends TestCase
{
    /**
     * Every request should ask for and send JSON, and identify the SDK and its version as user agent.
     */
    public function test_headers_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse([]));

        $restClient->get('some_resource');

        $this->assertSame('application/json', $this->lastApiRequest()->getHeaderLine('Accept'));
        $this->assertSame('application/json', $this->lastApiRequest()->getHeaderLine('Content-Type'));
        $this->assertSame(
            OrigamiRestClient::DEFAULT_USER_AGENT_PREFIX.'/'.OrigamiApiSdk::getVersion(),
            $this->lastApiRequest()->getHeaderLine('User-Agent'),
        );
    }

    /**
     * A custom user agent should be sent, followed by the SDK's own one.
     */
    public function test_headers_case_2(): void
    {
        $restClient = new OrigamiGuestRestClient('http://api.test', 'MyApp/1.0');
        $this->useMockedGuzzleClient($restClient, [$this->jsonResponse([])]);

        $restClient->get('some_resource');

        $this->assertSame(
            'MyApp/1.0 ('.OrigamiRestClient::DEFAULT_USER_AGENT_PREFIX.'/'.OrigamiApiSdk::getVersion().')',
            $this->lastApiRequest()->getHeaderLine('User-Agent'),
        );
    }

    /**
     * Every resource should be prefixed with `v1/`.
     */
    public function test_url_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse([]));

        $restClient->get('some_resource');

        $this->assertLastApiRequestWas('GET', 'some_resource');
    }

    /**
     * The default Guzzle client should use the API uri as base uri, without its trailing slash.
     */
    public function test_url_case_2(): void
    {
        $restClient = new OrigamiGuestRestClient('http://api.test/');

        $this->assertSame('http://api.test', (string) $restClient->getGuzzleClient()->getConfig('base_uri'));
    }

    /**
     * An API error should be thrown as the exception matching its http status, holding the error details.
     *
     * @param  class-string<OrigamiApiSingleException>  $expectedExceptionClass
     */
    #[DataProvider('requestErrorByHttpStatusProvider')]
    public function test_request_error_case_1(int $status, string $expectedExceptionClass): void
    {
        $restClient = $this->mockGuestRestClientReturning(
            $this->mockApiErrorsResponse($status, $this->mockApiErrorData($status, 'Some error.', '999999', 'email')),
        );

        $exception = $this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), $expectedExceptionClass);

        $this->assertSame($status, $exception->getErrorDto()->httpStatusCode);
        $this->assertSame('Some error.', $exception->getErrorDto()->message);
        $this->assertSame('999999', $exception->getOrigamiApiErrorCode());
        $this->assertSame('email', $exception->getErrorDto()->field);
    }

    public static function requestErrorByHttpStatusProvider(): array
    {
        return [
            'bad request'          => [400, OrigamiApiBadRequestException::class],
            'unauthorized'         => [401, OrigamiApiUnauthorizedException::class],
            'forbidden'            => [403, OrigamiApiForbiddenException::class],
            'not found'            => [404, OrigamiApiNotFoundException::class],
            'conflict'             => [409, OrigamiApiConflictException::class],
            'unprocessable entity' => [422, OrigamiApiUnprocessableEntityException::class],
            'too many requests'    => [429, OrigamiApiTooManyRequestsException::class],
            'other client error'   => [418, OrigamiApiClientErrorException::class],
        ];
    }

    /**
     * A known Origami error code should take precedence over the http status to choose the exception.
     */
    public function test_request_error_case_2(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->mockApiErrorsResponse(
            400,
            $this->mockApiErrorData(400, 'Unauthorized.', OrigamiApiErrorCodeEnum::UNAUTHORIZED->value),
        ));

        $this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), OrigamiApiUnauthorizedException::class);
    }

    /**
     * Several API errors should be thrown together, holding every one of them.
     */
    public function test_request_error_case_3(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->mockApiErrorsResponse(
            422,
            $this->mockApiErrorData(422, 'Email is invalid.', '999999', 'email'),
            $this->mockApiErrorData(422, 'Name is required.', '999999', 'name'),
        ));

        $exception = $this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), OrigamiApiMultipleException::class);

        $this->assertSame(['email', 'name'], $exception->getErrorDtos()->pluck('field')->all());
    }

    /**
     * A server error should be thrown as unknown, still holding the error details.
     */
    public function test_request_error_case_4(): void
    {
        $restClient = $this->mockGuestRestClientReturning(
            $this->mockApiErrorsResponse(500, $this->mockApiErrorData(500, 'Server error.', '999999')),
        );

        $exception = $this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), OrigamiApiUnknownException::class);

        $this->assertSame('Server error.', $exception->errorDto?->message);
    }

    /**
     * A JSON error response not following the Origami API error format should be thrown as unknown, with its body
     * still readable afterwards.
     */
    public function test_request_error_case_5(): void
    {
        $restClient = $this->mockGuestRestClientReturning(new Response(400, [], '{"message":"Bad request."}'));

        $exception = $this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), OrigamiApiUnknownException::class);

        $this->assertNull($exception->errorDto);
        $this->assertSame('{"message":"Bad request."}', $exception->getPrevious()->getResponse()->getBody()->getContents());
    }

    /**
     * A non-JSON error response (ex: a proxy's HTML error page) should be thrown as unknown too.
     */
    public function test_request_error_case_6(): void
    {
        $restClient = $this->mockGuestRestClientReturning(new Response(502, [], '<html>Bad Gateway</html>'));

        $this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), OrigamiApiUnknownException::class);
    }

    /**
     * A failure to reach the API (no response at all) should be thrown as an http client error.
     */
    public function test_request_error_case_7(): void
    {
        $restClient = $this->mockGuestRestClientReturning(
            new ConnectException('Connection refused.', new Request('GET', 'v1/some_resource')),
        );

        $this->assertThatExceptionWasThrown(fn () => $restClient->get('some_resource'), HttpClientException::class);
    }
}
