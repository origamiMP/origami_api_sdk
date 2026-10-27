<?php

namespace Tests\Unit\Repositories\Api;

use GuzzleHttp\Psr7\Response;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\RestApiRepositoryFixture;
use Tests\TestCase;

/**
 * Behavior shared by every Origami repository, tested through a fixture (which adds nothing to it).
 */
class RestApiRepositoryTest extends TestCase
{
    private RestApiRepositoryFixture $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new RestApiRepositoryFixture($this->mockGuestRestClientReturning());
    }

    /**
     * A JSON object body should be decoded as an object.
     */
    public function test_decode_response_case_1(): void
    {
        $responseContent = $this->repository->decodeResponse($this->jsonResponse(['data' => ['id' => 42]]));

        $this->assertEquals((object) ['data' => (object) ['id' => 42]], $responseContent);
    }

    /**
     * An empty list body should be decoded as an empty object, since PHP encodes an empty map as [].
     */
    public function test_decode_response_case_2(): void
    {
        $responseContent = $this->repository->decodeResponse($this->jsonResponse([]));

        $this->assertEquals((object) [], $responseContent);
    }

    /**
     * A body which is not JSON at all (ex: a proxy's HTML page, or an empty body) should be thrown as unknown,
     * keeping the JSON error as previous.
     */
    #[DataProvider('invalidJsonBodyProvider')]
    public function test_decode_response_case_3(string $body): void
    {
        $exception = $this->assertThatExceptionWasThrown(
            fn () => $this->repository->decodeResponse(new Response(200, [], $body)),
            OrigamiApiUnknownException::class,
            'Unreadable response from Origami API : HTTP 200, the body is not valid JSON',
        );

        $this->assertInstanceOf(\JsonException::class, $exception->getPrevious());
    }

    /**
     * A valid JSON body which is not an object should be thrown as unknown.
     */
    #[DataProvider('nonObjectJsonBodyProvider')]
    public function test_decode_response_case_4(string $body): void
    {
        $exception = $this->assertThatExceptionWasThrown(
            fn () => $this->repository->decodeResponse(new Response(200, [], $body)),
            OrigamiApiUnknownException::class,
            'Unreadable response from Origami API : HTTP 200, the body is not a JSON object',
        );

        $this->assertNull($exception->getPrevious());
    }

    /**
     * A JSON list body should be decoded as a list, its objects decoded as objects.
     */
    public function test_decode_list_response_case_1(): void
    {
        $responseContent = $this->repository->decodeListResponse($this->jsonResponse(['COMPANY', ['id' => 42]]));

        $this->assertEquals(['COMPANY', (object) ['id' => 42]], $responseContent);
    }

    public function test_decode_list_response_case_2(): void
    {
        $responseContent = $this->repository->decodeListResponse($this->jsonResponse([]));

        $this->assertSame([], $responseContent);
    }

    /**
     * A body which is not JSON at all should be thrown as unknown, keeping the JSON error as previous.
     */
    #[DataProvider('invalidJsonBodyProvider')]
    public function test_decode_list_response_case_3(string $body): void
    {
        $exception = $this->assertThatExceptionWasThrown(
            fn () => $this->repository->decodeListResponse(new Response(200, [], $body)),
            OrigamiApiUnknownException::class,
            'Unreadable response from Origami API : HTTP 200, the body is not valid JSON',
        );

        $this->assertInstanceOf(\JsonException::class, $exception->getPrevious());
    }

    /**
     * A valid JSON body which is not a list should be thrown as unknown.
     */
    #[DataProvider('nonListJsonBodyProvider')]
    public function test_decode_list_response_case_4(string $body): void
    {
        $exception = $this->assertThatExceptionWasThrown(
            fn () => $this->repository->decodeListResponse(new Response(200, [], $body)),
            OrigamiApiUnknownException::class,
            'Unreadable response from Origami API : HTTP 200, the body is not a JSON list',
        );

        $this->assertNull($exception->getPrevious());
    }

    /**
     * A decoded response with a `data` key should give its content.
     */
    public function test_get_response_content_data_or_empty_object_case_1(): void
    {
        $data = $this->repository->getResponseContentDataOrEmptyObject((object) ['data' => (object) ['id' => 42]]);

        $this->assertEquals((object) ['id' => 42], $data);
    }

    /**
     * A decoded response without `data` key should give an empty object.
     */
    public function test_get_response_content_data_or_empty_object_case_2(): void
    {
        $data = $this->repository->getResponseContentDataOrEmptyObject((object) ['id' => 42]);

        $this->assertEquals((object) [], $data);
    }

    public static function invalidJsonBodyProvider(): array
    {
        return [
            'html page'  => ['<html>Bad Gateway</html>'],
            'empty body' => [''],
            'truncated'  => ['{"data": {"id": 4'],
        ];
    }

    public static function nonObjectJsonBodyProvider(): array
    {
        return [
            'non-empty list' => ['["COMPANY"]'],
            'string'         => ['"some string"'],
            'number'         => ['42'],
            'null'           => ['null'],
        ];
    }

    public static function nonListJsonBodyProvider(): array
    {
        return [
            'object'       => ['{"data": []}'],
            'empty object' => ['{}'],
            'string'       => ['"some string"'],
            'number'       => ['42'],
            'null'         => ['null'],
        ];
    }
}
