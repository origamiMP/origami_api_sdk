<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api;

use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\RestClientRepository;
use Psr\Http\Message\ResponseInterface;

abstract class RestApiRepository
{
    public function __construct(protected RestClientRepository $restClient)
    {
        //
    }

    /**
     * @throws OrigamiApiUnknownException
     */
    protected function decodeResponse(ResponseInterface $response): object
    {
        try {
            $responseContent = json_decode((string) $response->getBody(), flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw OrigamiApiUnknownException::createFromUnreadableResponse($response, $e);
        }

        // PHP encodes an empty map as [], ex: no feature flag
        if ($responseContent === []) {
            return (object) [];
        }

        if (! is_object($responseContent)) {
            throw OrigamiApiUnknownException::createFromUnreadableResponse($response);
        }

        return $responseContent;
    }
}
