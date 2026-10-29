<?php

namespace Tests\Unit\Traits\ParamBags;

use Tests\Fixtures\PaginationParamBagFixture;
use Tests\TestCase;

/**
 * Behavior shared by every param bag with pagination, tested through a fixture (which adds nothing to it).
 */
class HasPaginationTest extends TestCase
{
    /**
     * A new param bag should send the default page and number of items per page.
     */
    public function test_query_case_1(): void
    {
        $paramBag = new PaginationParamBagFixture;

        $guzzleParams = $paramBag->asGuzzleParams();

        $this->assertSame(['page' => 1, 'item_per_page' => 20], $guzzleParams['query']);
    }

    /**
     * A number of items per page above 100 should be capped to 100.
     */
    public function test_set_per_page_case_1(): void
    {
        $paramBag = new PaginationParamBagFixture;

        $paramBag->setPerPage(150);

        $this->assertSame(100, $paramBag->getPerPage());
    }

    /**
     * Disabling the pagination should send `without_pagination`.
     */
    public function test_set_without_pagination_case_1(): void
    {
        $paramBag = new PaginationParamBagFixture;

        $paramBag->setWithoutPagination(true);

        $this->assertTrue($paramBag->isWithoutPagination());
        $this->assertTrue($paramBag->asGuzzleParams()['query']['without_pagination']);
    }

    /**
     * Enabling the pagination again should stop sending `without_pagination`.
     */
    public function test_set_without_pagination_case_2(): void
    {
        $paramBag = new PaginationParamBagFixture;
        $paramBag->setWithoutPagination(true);

        $paramBag->setWithoutPagination(false);

        $this->assertFalse($paramBag->isWithoutPagination());
        $this->assertArrayNotHasKey('without_pagination', $paramBag->asGuzzleParams()['query']);
    }
}
