<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest;

use OrigamiMp\OrigamiApiSdk\Repositories\Api\RestApiRepository;
use OrigamiMp\OrigamiApiSdk\Repositories\Client\Guest\OrigamiGuestRestClient;

abstract class OrigamiGuestApiRepository extends RestApiRepository
{
    public function __construct(OrigamiGuestRestClient $restClient)
    {
        parent::__construct($restClient);
    }
}
