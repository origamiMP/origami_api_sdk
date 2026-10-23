<?php

namespace Tests\Unit\ParamBags;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\JsonParamBagFixture;
use Tests\TestCase;

/**
 * Behavior shared by every param bag, tested through a fixture (which adds nothing to it).
 *
 * Guards the declaration of the API keys too: every param bag of the SDK must declare the API key of each property
 * it sends, since nothing is deduced from the property names. A property left out would silently not be sent.
 */
class ParamBagTest extends TestCase
{
    /**
     * A date without a specific cast on the param bag should be sent with its time, as `Y-m-d H:i:s`.
     */
    public function test_json_case_1(): void
    {
        $paramBag = new JsonParamBagFixture;
        $paramBag->date = Carbon::parse('2024-05-12 16:12:56');

        $guzzleParams = $paramBag->asGuzzleParams();

        $this->assertSame(['date' => '2024-05-12 16:12:56'], $guzzleParams['json']);
    }

    /**
     * Every public property of a param bag should be declared with its API key, and every declared property should
     * exist.
     *
     * @param  class-string<ParamBag>  $paramBagClass
     */
    #[DataProvider('paramBagProvider')]
    public function test_api_keys_case_1(string $paramBagClass): void
    {
        $reflection = new \ReflectionClass($paramBagClass);
        $declaredProperties = array_values($reflection->getMethod('getApiKeysToProperties')->invoke($reflection->newInstanceWithoutConstructor()));
        $publicProperties = array_map(fn (\ReflectionProperty $property) => $property->getName(), $reflection->getProperties(\ReflectionProperty::IS_PUBLIC));

        $this->assertSame([], array_values(array_diff($publicProperties, $declaredProperties)), 'Public properties without API key.');
        $this->assertSame([], array_values(array_filter($declaredProperties, fn (string $property) => ! $reflection->hasProperty($property))), 'Declared properties that do not exist.');
    }

    /**
     * @return array<string, array{class-string<ParamBag>}>
     */
    public static function paramBagProvider(): array
    {
        return collect(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(__DIR__, 3).'/src/ParamBags')))
            ->filter(fn (\SplFileInfo $file) => $file->getExtension() === 'php')
            ->map(fn (\SplFileInfo $file) => 'OrigamiMp\\OrigamiApiSdk\\'.str_replace(['/', '.php'], ['\\', ''], substr($file->getPathname(), strlen(dirname(__DIR__, 3).'/src/'))))
            ->filter(fn (string $class) => ! (new \ReflectionClass($class))->isAbstract())
            ->mapWithKeys(fn (string $class) => [class_basename($class) => [$class]])
            ->sortKeys()
            ->all();
    }
}
