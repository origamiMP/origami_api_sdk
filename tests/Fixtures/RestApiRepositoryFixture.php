<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\Repositories\Api\RestApiRepository;
use Psr\Http\Message\ResponseInterface;

/**
 * Minimal RestApiRepository, exposing its protected helpers to test the behavior shared by every repository.
 */
class RestApiRepositoryFixture extends RestApiRepository
{
    public function decodeResponse(ResponseInterface $response): object
    {
        return parent::decodeResponse($response);
    }

    public function decodeListResponse(ResponseInterface $response): array
    {
        return parent::decodeListResponse($response);
    }

    public function getResponseContentDataOrEmptyObject(object $responseContent): object
    {
        return parent::getResponseContentDataOrEmptyObject($responseContent);
    }
}
