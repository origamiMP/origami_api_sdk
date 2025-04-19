<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client;

use GuzzleHttp\Exception\BadResponseException;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorsDto;

abstract class OrigamiRestClient extends RestClientRepository
{
    /**
     * Domain of the Origami API uri.
     * Ex : https://testing.sandbox-origami.net
     */
    private string $apiUri;

    public function __construct(string $apiUri)
    {
        $this->apiUri = $apiUri;
    }

    protected function getRestApiBaseUrl(): string
    {
        return trim($this->apiUri, '/');
    }

    protected function handleRequestError(BadResponseException $exception): void
    {
        $responseContent = $exception->getResponse()->getBody()->getContents();
        // Rewind for next times some code will try to read body content
        $exception->getResponse()->getBody()->rewind();

        try {
            $errorDto = new OrigamiApiErrorsDto(json_decode($responseContent));

            $errorDto->throwCorrespondingException();
        } catch (\Throwable $exception) {
            // TODO throw generic exception
            throw new \Exception('NOT RECOGNIZED EXCEPTION');
        }
    }
}
