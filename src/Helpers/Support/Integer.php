<?php

namespace OrigamiMp\OrigamiApiSdk\Helpers\Support;

class Integer
{
    public static function unique(): int
    {
        return hexdec(uniqid());
    }
}
