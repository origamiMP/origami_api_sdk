<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCorrespondingException;

/**
 * Minimal class with a corresponding exception, to test the HasCorrespondingException trait: its exception is a
 * `LogicException` with the message `Some error`.
 */
class CorrespondingExceptionFixture
{
    use HasCorrespondingException;

    public function getCorrespondingException(): \Throwable
    {
        return new \LogicException('Some error');
    }
}
