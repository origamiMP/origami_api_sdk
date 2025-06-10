<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Client;

use GuzzleHttp\Exception\GuzzleException;

class HttpClientException extends \Exception
{
    public function __construct(GuzzleException $previous)
    {
        parent::__construct(
            'The request could not be processed by the HTTP client used by Origami API SDK.',
            previous: $previous
        );
    }
}
