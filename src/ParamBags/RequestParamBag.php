<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags;

use Closure;
use OrigamiMp\OrigamiApiSdk\Enums\Http\HttpRequestParamsTypeEnum;

abstract class RequestParamBag extends ParamBag
{
    public function asGuzzleParams(): array
    {
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

    protected function getHeaders(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function getPropertiesToApiKeys(): array
    {
        return array_merge(
            $this->getJsonRequestParamsList(),
            $this->getFormRequestParamsList(),
            $this->getQueryRequestParamsList(),
        );
    }

    /**
     * @return array<string, string|Closure(mixed): array<string, mixed>> property name => API key, or closure (see ParamBag::getPropertiesToApiKeys())
     */
    protected function getJsonRequestParamsList(): array
    {
        return [];
    }

    /**
     * @return array<string, string|Closure(mixed): array<string, mixed>> property name => API key, or closure (see ParamBag::getPropertiesToApiKeys())
     */
    protected function getFormRequestParamsList(): array
    {
        return [];
    }

    /**
     * @return array<string, string|Closure(mixed): array<string, mixed>> property name => API key, or closure (see ParamBag::getPropertiesToApiKeys())
     */
    protected function getQueryRequestParamsList(): array
    {
        return [];
    }

    protected function getHeadersAsGuzzleParams(): array
    {
        return ['headers' => $this->getHeaders()];
    }

    protected function getTypedRequestParamsAsGuzzleParams(
        HttpRequestParamsTypeEnum $requestParamsTypeEnum,
        array $requestParamsList,
    ): array {
        // TODO DEV : Add validation mechanism with rules as $requestParamsList values
        // TODO DEV : Throw custom validation exception if not validated

        $paramsAsEncodableArray = $this->asEncodableArray($requestParamsList);

        return ! empty($paramsAsEncodableArray)
            ? [$requestParamsTypeEnum->value => $paramsAsEncodableArray]
            : [];
    }
}
