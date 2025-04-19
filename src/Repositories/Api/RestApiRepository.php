<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api;

use OrigamiMp\OrigamiApiSdk\Repositories\Client\RestClientRepository;

abstract class RestApiRepository
{
    public function __construct(protected RestClientRepository $restClient)
    {
        //
    }
}
