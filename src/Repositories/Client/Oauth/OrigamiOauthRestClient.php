<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Client\Oauth;

use OrigamiMp\OrigamiApiSdk\Enums\Http\HttpRequestMethodEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\OrigamiRestClient;

class OrigamiOauthRestClient extends OrigamiRestClient
{
    /**
     * ID of the API auth client. Supplied by Origami.
     */
    private string $apiClientId;

    /**
     * Secret of the API auth client. Supplied by Origami.
     */
    private string $apiClientSecret;

    public function __construct(string $apiUri, string $apiClientId, string $apiClientSecret)
    {
        parent::__construct($apiUri);

        $this->apiClientId = $apiClientId;
        $this->apiClientSecret = $apiClientSecret;
    }

    public function getApiClientId(): string
    {
        return $this->apiClientId;
    }

    public function getApiClientSecret(): string
    {
        return $this->apiClientSecret;
    }

    protected function getGuzzleParamsForRequest(HttpRequestMethodEnum $method, ?RequestParamBag $paramBag): array
    {
        $guzzleParamsFromParamBag = $paramBag?->asGuzzleParams() ?? [];

        return $this->mergeAdditionalHeadersInGuzzleParams($guzzleParamsFromParamBag);
    }
}
