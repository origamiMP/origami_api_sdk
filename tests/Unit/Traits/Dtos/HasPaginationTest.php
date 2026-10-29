<?php

namespace Tests\Unit\Traits\Dtos;

use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\Fixtures\PaginationDtoFixture;
use Tests\TestCase;

/**
 * Behavior shared by every paginated list Dto, tested through a fixture (which adds nothing to it).
 */
class HasPaginationTest extends TestCase
{
    /**
     * toArray() should expose the items and nest the pagination under meta.pagination, with camelCase keys.
     */
    public function test_to_array_case_1(): void
    {
        $dto = $this->makeFixture($this->mockApiPaginatedListData([['id' => 1], ['id' => 2]]));

        $array = $dto->toArray();

        $this->assertSame([1, 2], array_column($array['data'], 'id'));
        $this->assertSame(
            ['total' => 2, 'count' => 2, 'perPage' => 2, 'currentPage' => 1, 'totalPages' => 1],
            $array['meta']['pagination'],
        );
    }

    private function makeFixture(array $apiData): PaginationDtoFixture
    {
        return new PaginationDtoFixture(Obj::fromArray($apiData));
    }
}
