<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags;

use OrigamiMp\OrigamiApiSdk\Enums\Http\HttpRequestParamsTypeEnum;

/**
 * This class is made to handle two use-cases :
 *
 * - If it only has one request params type, it only requires the getRequestParamsMainType()
 * method to be defined, along with typed properties representing the request params.
 * Example : TODO
 *
 * - If it has multiple request params types, following methods should be defined along with
 * typed properties representing the request params : getJsonRequestParamsList(), getFormRequestParamsList
 * and getQueryRequestParamsList.
 * Example : TODO
 */
abstract class RequestParamBag extends ParamBag
{
    public function asGuzzleParams(): array
    {
        if (! $this->hasMultipleRequestParamsTypes()) {
            return $this->getTypedRequestParamsAsGuzzleParams($this->getRequestParamsMainType());
        }

        $jsonParams = $this->getTypedRequestParamsAsGuzzleParams(
            HttpRequestParamsTypeEnum::JSON,
            $this->getJsonRequestParamsList(),
        );
        $formParams = $this->getTypedRequestParamsAsGuzzleParams(
            HttpRequestParamsTypeEnum::FORM,
            $this->getFormRequestParamsList(),
        );
        $queryParams = $this->getTypedRequestParamsAsGuzzleParams(
            HttpRequestParamsTypeEnum::QUERY,
            $this->getQueryRequestParamsList(),
        );

        $headers = $this->getHeadersAsGuzzleParams();

        return array_merge(
            ! empty($jsonParams) ? $jsonParams : [],
            ! empty($formParams) ? $formParams : [],
            ! empty($queryParams) ? $queryParams : [],
            ! empty($headers) ? $headers : [],
        );
    }

    protected function getRequestParamsMainType(): HttpRequestParamsTypeEnum
    {
        return HttpRequestParamsTypeEnum::JSON;
    }

    protected function hasMultipleRequestParamsTypes(): bool
    {
        return ! empty($this->getJsonRequestParamsList())
            || ! empty($this->getFormRequestParamsList())
            || ! empty($this->getQueryRequestParamsList());
    }

    protected function getTypedRequestParamsAsGuzzleParams(
        HttpRequestParamsTypeEnum $requestParamsTypeEnum,
        ?array $requestParamsList = null,
    ): array {
        $paramsAsEncodableArray = $this->asEncodableArray($requestParamsList);

        return ! empty($paramsAsEncodableArray)
            ? [$requestParamsTypeEnum->value => $paramsAsEncodableArray]
            : [];
    }

    protected function getHeadersAsGuzzleParams(): array
    {
        return ['headers' => $this->getHeaders()];
    }

    protected function getHeaders(): array
    {
        return [];
    }

    protected function getJsonRequestParamsList(): array
    {
        return [];
    }

    protected function getFormRequestParamsList(): array
    {
        return [];
    }

    protected function getQueryRequestParamsList(): array
    {
        return [];
    }
}
