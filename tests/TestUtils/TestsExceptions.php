<?php

namespace Tests\TestUtils;

trait TestsExceptions
{
    /**
     * Asserts that calling $callableToTest throws a $exceptionClass, with $message when given. The exception is
     * returned to assert anything else on it (ex: its error details, its previous exception).
     *
     * @template T of \Throwable
     *
     * @param  class-string<T>  $exceptionClass
     * @return T
     */
    protected function assertThatExceptionWasThrown(
        callable $callableToTest,
        string $exceptionClass,
        ?string $message = null,
    ): \Throwable {
        try {
            $callableToTest();
        } catch (\Throwable $e) {
            $this->assertInstanceOf(
                $exceptionClass,
                $e,
                "{$exceptionClass} was expected, ".$e::class." was thrown instead: {$e->getMessage()}",
            );
            ! is_null($message) && $this->assertSame($message, $e->getMessage());

            return $e;
        }

        $this->fail("{$exceptionClass} was not thrown.");
    }
}
