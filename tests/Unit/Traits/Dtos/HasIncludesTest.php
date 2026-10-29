<?php

namespace Tests\Unit\Traits\Dtos;

use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\IncludesDtoFixture;
use Tests\TestCase;

/**
 * Behavior shared by every Dto with includes, tested through a fixture (which adds nothing to it).
 */
class HasIncludesTest extends TestCase
{
    /**
     * An include declared on the Dto should be available, and so should a nested one when the included Dto declares
     * it too.
     */
    #[DataProvider('isIncludeAvailableProvider')]
    public function test_is_include_available_case_1(string $include, bool $expectedAvailability): void
    {
        $this->assertSame($expectedAvailability, IncludesDtoFixture::isIncludeAvailable($include));
    }

    public static function isIncludeAvailableProvider(): array
    {
        return [
            'declared'                         => ['children', true],
            'nested declared'                  => ['children.parent', true],
            'not declared'                     => ['unknown', false],
            'nested not declared'              => ['children.unknown', false],
            'nested in a Dto without includes' => ['translation.anything', false],
        ];
    }

    /**
     * A collection include should be read from its `data` key, each of its items as the given Dto.
     */
    public function test_collect_included_case_1(): void
    {
        $dto = $this->makeFixture(['id' => 1, 'children' => ['data' => [['id' => 2], ['id' => 3]]]]);

        $this->assertContainsOnlyInstancesOf(IncludesDtoFixture::class, $dto->children);
        $this->assertSame([2, 3], $dto->children->pluck('id')->all());
    }

    public function test_collect_included_case_2(): void
    {
        $dto = $this->makeFixture(['id' => 1, 'children' => ['data' => []]]);

        $this->assertTrue($dto->children->isEmpty());
    }

    /**
     * A collection include without `data` key should make the Dto not constructable.
     */
    public function test_collect_included_case_3(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->makeFixture(['id' => 1, 'children' => ['items' => []]]),
            ApiResponseDtoNotConstructableException::class,
            "[IncludesDtoFixture] Could not assign data 'children' to property: "
            .'[IncludesDtoFixture] Object part from API response does not have mandatory `data` field.',
        );
    }

    /**
     * A single item include should be read from its `data` key, as the given Dto.
     */
    public function test_make_included_case_1(): void
    {
        $dto = $this->makeFixture(['id' => 1, 'parent' => ['data' => ['id' => 2]]]);

        $this->assertInstanceOf(IncludesDtoFixture::class, $dto->parent);
        $this->assertSame(2, $dto->parent->id);
    }

    /**
     * A single item include without `data` key should make the Dto not constructable.
     */
    public function test_make_included_case_2(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->makeFixture(['id' => 1, 'parent' => ['id' => 2]]),
            ApiResponseDtoNotConstructableException::class,
            "[IncludesDtoFixture] Could not assign data 'parent' to property: "
            .'[IncludesDtoFixture] Object part from API response does not have mandatory `data` field.',
        );
    }

    private function makeFixture(array $apiData): IncludesDtoFixture
    {
        return new IncludesDtoFixture(Obj::fromArray($apiData));
    }
}
