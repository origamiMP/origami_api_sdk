<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use OrigamiMp\OrigamiApiSdk\Enums\Http\HttpRequestMethodEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Http\HttpRequestParamsTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use Psr\Http\Message\ResponseInterface;

abstract class RestClientRepository
{
    protected GuzzleClient $guzzleClient;

    /**
     * @throws GuzzleException
     */
    public function get(
        string $resource,
        ?RequestParamBag $paramBag = null,
        bool $doBeforeApiCall = true,
        bool $doAfterApiCall = true,
    ): ResponseInterface {
        return $this->callRestApi(
            HttpRequestMethodEnum::GET,
            $resource,
            $paramBag,
            $doBeforeApiCall,
            $doAfterApiCall,
        );
    }

    /**
     * @throws GuzzleException
     */
    public function post(
        string $resource,
        ?RequestParamBag $paramBag = null,
        bool $doBeforeApiCall = true,
        bool $doAfterApiCall = true,
    ): ResponseInterface {
        return $this->callRestApi(
            HttpRequestMethodEnum::POST,
            $resource,
            $paramBag,
            $doBeforeApiCall,
            $doAfterApiCall,
        );
    }

    /**
     * @throws GuzzleException
     */
    public function put(
        string $resource,
        ?RequestParamBag $paramBag = null,
        bool $doBeforeApiCall = true,
        bool $doAfterApiCall = true,
    ): ResponseInterface {
        return $this->callRestApi(
            HttpRequestMethodEnum::PUT,
            $resource,
            $paramBag,
            $doBeforeApiCall,
            $doAfterApiCall,
        );
    }

    /**
     * @throws GuzzleException
     */
    public function patch(
        string $resource,
        ?RequestParamBag $paramBag = null,
        bool $doBeforeApiCall = true,
        bool $doAfterApiCall = true,
    ): ResponseInterface {
        return $this->callRestApi(
            HttpRequestMethodEnum::PATCH,
            $resource,
            $paramBag,
            $doBeforeApiCall,
            $doAfterApiCall,
        );
    }

    /**
     * @throws GuzzleException
     */
    public function delete(
        string $resource,
        ?RequestParamBag $paramBag = null,
        bool $doBeforeApiCall = true,
        bool $doAfterApiCall = true,
    ): ResponseInterface {
        return $this->callRestApi(
            HttpRequestMethodEnum::DELETE,
            $resource,
            $paramBag,
            $doBeforeApiCall,
            $doAfterApiCall,
        );
    }

    public function getGuzzleClient(): GuzzleClient
    {
        return $this->guzzleClient ?? ($this->guzzleClient = $this->initDefaultGuzzleClient());
    }

    public function setGuzzleClient(GuzzleClient $guzzleClient): void
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @throws GuzzleException
     */
    protected function callRestApi(
        HttpRequestMethodEnum $method,
        string $resource,
        ?RequestParamBag $paramBag,
        bool $doBeforeApiCall = true,
        bool $doAfterApiCall = true,
    ): ResponseInterface {
        $guzzleParams = $this->getGuzzleParamsForRequest($method, $paramBag);

        $doBeforeApiCall && $this->beforeApiCall($method, $resource, $paramBag);

        try {
            $response = $this->makeGuzzleRequest(
                $method->value,
                $this->getUrlResourcePart($resource),
                $guzzleParams
            );

            $doAfterApiCall && $this->afterApiCall($response, $method, $resource, $paramBag);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $doAfterApiCall && $this->afterApiCall($response, $method, $resource, $paramBag, $e);
            $this->handleRequestError($e);
        }

        return $response;
    }

    /**
     * @throws GuzzleException
     */
    protected function makeGuzzleRequest(string $method, string $resource, array $guzzleParams = []): ResponseInterface
    {
        $guzzleClient = $this->getGuzzleClient();

        return $guzzleClient->request($method, $resource, $guzzleParams);
    }

    protected function initDefaultGuzzleClient(): GuzzleClient
    {
        return new GuzzleClient(['base_uri' => $this->getRestApiBaseUrl()]);
    }

    protected function beforeApiCall(
        HttpRequestMethodEnum $method,
        string $resource,
        ?RequestParamBag $paramBag,
    ): void {
        //
    }

    protected function afterApiCall(
        ResponseInterface &$response,
        HttpRequestMethodEnum $method,
        string $resource,
        ?RequestParamBag $paramBag,
        ?BadResponseException $exception = null
    ): void {
        //
    }

    abstract protected function handleRequestError(BadResponseException $guzzleException): void;

    protected function getRequestParamsTypeDependingOnRequestMethod(
        HttpRequestMethodEnum $method
    ): HttpRequestParamsTypeEnum {
        return match ($method) {
            HttpRequestMethodEnum::GET,
            HttpRequestMethodEnum::DELETE => HttpRequestParamsTypeEnum::QUERY,
            default                       => HttpRequestParamsTypeEnum::JSON,
        };
    }

    protected function getUrlResourcePart(string $resource): string
    {
        $trimmedPrefix = rtrim($this->getResourcePrefix(), '/');

        return ltrim("$trimmedPrefix/$resource", '/');
    }

    protected function getResourcePrefix(): string
    {
        return '';
    }

    protected function mergeAdditionalHeadersInGuzzleParams(array $guzzleParams): array
    {
        $headersFromParamBag = Arr::get($guzzleParams, 'headers', []);

        $finalHeaders = array_merge(
            $this->getAdditionalHeaders(),
            $headersFromParamBag,
        );

        $guzzleParams['headers'] = $finalHeaders;

        return $guzzleParams;
    }

    abstract protected function getGuzzleParamsForRequest(
        HttpRequestMethodEnum $method,
        ?RequestParamBag $paramBag,
    ): array;

    abstract protected function getRestApiBaseUrl(): string;

    /**
     * Headers that will be added to every request made by this client. They can be overridden by the ones
     * specified in the RequestParamBag.
     */
    abstract protected function getAdditionalHeaders(): array;
}
