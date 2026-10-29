<?php

namespace Tests\Unit\Exceptions;

use OrigamiMp\OrigamiApiSdk\Exceptions\OrigamiApiSdkException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Guards the common parent of the exceptions: every exception of the SDK must extend `OrigamiApiSdkException`, so
 * that its users can catch them all at once. An exception extending `\Exception` directly would escape that catch.
 */
class OrigamiApiSdkExceptionTest extends TestCase
{
    /**
     * Every exception of the SDK should extend OrigamiApiSdkException, so that its users can catch them all at once.
     *
     * @param  class-string<\Throwable>  $exceptionClass
     */
    #[DataProvider('exceptionProvider')]
    public function test_parent_case_1(string $exceptionClass): void
    {
        $this->assertTrue(is_subclass_of($exceptionClass, OrigamiApiSdkException::class));
    }

    /**
     * @return array<string, array{class-string<\Throwable>}>
     */
    public static function exceptionProvider(): array
    {
        return collect(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(__DIR__, 3).'/src/Exceptions')))
            ->filter(fn (\SplFileInfo $file) => $file->getExtension() === 'php')
            ->map(fn (\SplFileInfo $file) => 'OrigamiMp\\OrigamiApiSdk\\'.str_replace(['/', '.php'], ['\\', ''], substr($file->getPathname(), strlen(dirname(__DIR__, 3).'/src/'))))
            ->reject(fn (string $class) => $class === OrigamiApiSdkException::class)
            ->mapWithKeys(fn (string $class) => [class_basename($class) => [$class]])
            ->sortKeys()
            ->all();
    }
}
