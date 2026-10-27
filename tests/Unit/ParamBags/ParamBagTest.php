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
     * A property declared with a cast closure should be sent as the API key / value pair the closure returns.
     */
    public function test_json_case_2(): void
    {
        $paramBag = new JsonParamBagFixture;
        $paramBag->dateOnly = Carbon::parse('2024-05-12 16:12:56');

        $guzzleParams = $paramBag->asGuzzleParams();

        $this->assertSame(['date_only' => '2024-05-12'], $guzzleParams['json']);
    }

    /**
     * A property declared with a cast closure but not set should not be sent.
     */
    public function test_json_case_3(): void
    {
        $paramBag = new JsonParamBagFixture;
        $paramBag->date = Carbon::parse('2024-05-12 16:12:56');

        $guzzleParams = $paramBag->asGuzzleParams();

        $this->assertSame(['date' => '2024-05-12 16:12:56'], $guzzleParams['json']);
    }

    /**
     * A cast closure not returning a single API key / value pair should throw a LogicException.
     */
    #[DataProvider('invalidCastResultProvider')]
    public function test_json_case_4(array $castResult): void
    {
        $paramBag = new JsonParamBagFixture;
        $paramBag->castResult = $castResult;

        $this->expectException(\LogicException::class);

        $paramBag->asGuzzleParams();
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
        $declaredProperties = array_keys($reflection->getMethod('getPropertiesToApiKeys')->invoke($reflection->newInstanceWithoutConstructor()));
        $publicProperties = array_map(fn (\ReflectionProperty $property) => $property->getName(), $reflection->getProperties(\ReflectionProperty::IS_PUBLIC));

        $this->assertSame([], array_values(array_diff($publicProperties, $declaredProperties)), 'Public properties without API key.');
        $this->assertSame([], array_values(array_filter($declaredProperties, fn (string $property) => ! $reflection->hasProperty($property))), 'Declared properties that do not exist.');
    }

    /**
     * @return array<string, array{array}>
     */
    public static function invalidCastResultProvider(): array
    {
        return [
            'no pair'     => [[]],
            'two pairs'   => [['date_only' => '2024-05-12', 'time_only' => '16:12:56']],
            'integer key' => [['2024-05-12']],
        ];
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
