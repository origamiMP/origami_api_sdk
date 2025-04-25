<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags;

use Illuminate\Support\Arr;
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

    protected function getHeadersAsGuzzleParams(): array
    {
        return ['headers' => $this->getHeaders()];
    }

    protected function getTypedRequestParamsAsGuzzleParams(
        HttpRequestParamsTypeEnum $requestParamsTypeEnum,
        array $requestParamsList,
    ): array {
        $paramsAsEncodableArray = $this->asEncodableArray(
            Arr::except($requestParamsList, self::propertiesToExcludeFromGuzzleParams())
        );

        return ! empty($paramsAsEncodableArray)
            ? [$requestParamsTypeEnum->value => $paramsAsEncodableArray]
            : [];
    }

    protected static function propertiesToExcludeFromGuzzleParams(): array
    {
        return [];
    }
}
