<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data;

use OrigamiMp\OrigamiApiSdk\Repositories\Api\RestApiRepository;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\Data\OrigamiDataRestClient;

abstract class OrigamiDataApiRepository extends RestApiRepository
{
    public function __construct(OrigamiDataRestClient $restClient)
    {
        parent::__construct($restClient);
    }

    protected function getResponseContentDataOrEmptyObject(object $responseContent): object
    {
        return $responseContent->data ?? (object) [];
    }

    protected function getResponseContentDataOrEmptyArray(object $responseContent): array
    {
        return $responseContent->data ?? [];
    }
}
