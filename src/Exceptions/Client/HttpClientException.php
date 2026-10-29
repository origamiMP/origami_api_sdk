<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Client;

use GuzzleHttp\Exception\GuzzleException;
use OrigamiMp\OrigamiApiSdk\Exceptions\OrigamiApiSdkException;

class HttpClientException extends OrigamiApiSdkException
{
    public function __construct(GuzzleException $previous)
    {
        parent::__construct(
            'The request could not be processed by the HTTP client used by Origami API SDK.',
            previous: $previous
        );
    }
}
