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
}
