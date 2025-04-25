<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client;

use GuzzleHttp\Exception\BadResponseException;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorsDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;

abstract class OrigamiRestClient extends RestClientRepository
{
    const DEFAULT_USER_AGENT_PREFIX = 'OrigamiApiSdk';

    /**
     * Domain of the Origami API uri.
     * Ex : https://testing.sandbox-origami.net
     */
    private string $apiUri;

    private string $userAgent;

    public function __construct(string $apiUri, ?string $userAgent = null)
    {
        $this->apiUri = $apiUri;
        $this->initUserAgent($userAgent);
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

    protected function initUserAgent(?string $userAgent = null): void
    {
        $defaultPrefix = self::DEFAULT_USER_AGENT_PREFIX;
        $sdkVersion = getOrigamiApiSdkVersion();
        $defaultUserAgent = "$defaultPrefix/$sdkVersion";

        $this->userAgent = ! is_null($userAgent)
            ? "$userAgent ($defaultUserAgent)"
            : $defaultUserAgent;
    }

    /**
     * {@inheritDoc}
     */
    protected function getAdditionalHeaders(): array
    {
        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent'   => $this->userAgent,
        ];
    }
}
