<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Http;

enum HttpRequestParamsTypeEnum: string
{
    case QUERY = 'query';

    case JSON = 'json';

    case FORM = 'form_params';
}
