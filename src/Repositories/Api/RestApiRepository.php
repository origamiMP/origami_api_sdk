<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api;

use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
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
     * Decodes a response whose body should be a JSON object.
     *
     * @throws OrigamiApiUnknownException
     */
    protected function decodeResponse(ResponseInterface $response): object
    {
        $responseContent = $this->decodeJson($response);

        // PHP encodes an empty map as [], ex: no feature flag
        if ($responseContent === []) {
            return (object) [];
        }

        if (! is_object($responseContent)) {
            throw OrigamiApiUnknownException::createFromUnreadableResponse($response, 'object');
        }

        return $responseContent;
    }

    /**
     * Decodes a response whose body should be a JSON list (ex: seller legal types).
     *
     * @throws OrigamiApiUnknownException
     */
    protected function decodeListResponse(ResponseInterface $response): array
    {
        $responseContent = $this->decodeJson($response);

        if (! is_array($responseContent) || ! array_is_list($responseContent)) {
            throw OrigamiApiUnknownException::createFromUnreadableResponse($response, 'list');
        }

        return $responseContent;
    }

    /**
     * Reads the `data` key of a decoded response, which holds the item of a Fractal response.
     *
     * @throws OrigamiApiResponseWithoutDataException
     */
    protected function getResponseContentData(object $responseContent): object
    {
        if (! isset($responseContent->data)) {
            throw new OrigamiApiResponseWithoutDataException;
        }

        return $responseContent->data;
    }

    /**
     * @throws OrigamiApiUnknownException
     */
    private function decodeJson(ResponseInterface $response): mixed
    {
        try {
            return json_decode((string) $response->getBody(), flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw OrigamiApiUnknownException::createFromUnreadableResponse($response, previous: $e);
        }
    }
}
