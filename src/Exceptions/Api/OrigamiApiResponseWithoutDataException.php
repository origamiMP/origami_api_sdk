<?php

namespace OrigamiMp\OrigamiApiSdk\Exceptions\Api;

class OrigamiApiResponseWithoutDataException extends OrigamiApiException
{
    public function __construct()
    {
        parent::__construct('Response from Origami API without data key');
    }
}
