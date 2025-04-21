<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client;

use GuzzleHttp\Exception\BadResponseException;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorsDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;

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

    /**
     * @throws OrigamiApiException
     */
    protected function handleRequestError(BadResponseException $guzzleException): void
    {
        $encodedResponseContent = $guzzleException->getResponse()->getBody()->getContents();
        // Rewind for next times some code will try to read body content
        $guzzleException->getResponse()->getBody()->rewind();

        $decodedResponseContent = json_decode($encodedResponseContent);

        try {
            $errorDto = new OrigamiApiErrorsDto($decodedResponseContent);

            $errorDto->throwCorrespondingException();
        } catch (ApiResponseDtoNotConstructableException $dtoNotConstructableException) {
            throw OrigamiApiUnknownException::createFromGuzzleBadResponse($guzzleException);
        }
    }
}
