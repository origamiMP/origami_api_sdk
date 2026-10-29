<?php

namespace Tests\Unit\Traits\Dtos;

use Tests\Fixtures\CorrespondingExceptionFixture;
use Tests\TestCase;

/**
 * Behavior shared by every class with a corresponding exception, tested through a fixture (which adds nothing to it).
 */
class HasCorrespondingExceptionTest extends TestCase
{
    /**
     * throwCorrespondingException() should throw the exception returned by getCorrespondingException().
     */
    public function test_throw_corresponding_exception_case_1(): void
    {
        $fixture = new CorrespondingExceptionFixture;

        $this->assertThatExceptionWasThrown(fn () => $fixture->throwCorrespondingException(), \LogicException::class, 'Some error');
    }
}
