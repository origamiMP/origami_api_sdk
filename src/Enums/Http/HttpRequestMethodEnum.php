<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Http;

enum HttpRequestMethodEnum: string
{
    case GET = 'GET';

    case POST = 'POST';

    case PUT = 'PUT';

    case PATCH = 'PATCH';

    case DELETE = 'DELETE';
}
