<?php

namespace Tests\Unit\Traits\ParamBags;

use Carbon\Carbon;
use Tests\Fixtures\FiltersParamBagFixture;
use Tests\TestCase;

/**
 * Behavior shared by every param bag with filters, tested through a fixture (which adds nothing to it).
 */
class HasFiltersTest extends TestCase
{
    /**
     * The available filters should be the base ones (creation and update dates), followed by the ones the param bag
     * adds.
     */
    public function test_get_available_filters_case_1(): void
    {
        $availableFilters = FiltersParamBagFixture::getAvailableFilters();

        $this->assertSame(['createdBefore', 'createdAfter', 'updatedBefore', 'updatedAfter', 'is_active'], $availableFilters);
    }

    /**
     * Base and additional filters should be kept with their values, as given.
     */
    public function test_with_filters_case_1(): void
    {
        $paramBag = new FiltersParamBagFixture;

        $paramBag->withFilters(['createdAfter' => '2024-05-12 16:12:56', 'is_active' => true]);

        $this->assertSame(['createdAfter' => '2024-05-12 16:12:56', 'is_active' => true], $paramBag->getFilters());
    }

    /**
     * A filter not available on the param bag should be dropped, keys being compared as given (no case conversion).
     */
    public function test_with_filters_case_2(): void
    {
        $paramBag = new FiltersParamBagFixture;

        $paramBag->withFilters(['is_active' => false, 'unknown_filter' => 'value', 'created_after' => '2024-05-12']);

        $this->assertSame(['is_active' => false], $paramBag->getFilters());
    }

    /**
     * Setting filters again should replace the previous ones, not add to them.
     */
    public function test_with_filters_case_3(): void
    {
        $paramBag = new FiltersParamBagFixture;
        $paramBag->withFilters(['is_active' => true]);

        $paramBag->withFilters(['updatedBefore' => '2024-05-12 16:12:56']);

        $this->assertSame(['updatedBefore' => '2024-05-12 16:12:56'], $paramBag->getFilters());
    }

    /**
     * Without any filter set, the filters should be empty.
     */
    public function test_get_filters_case_1(): void
    {
        $paramBag = new FiltersParamBagFixture;

        $this->assertSame([], $paramBag->getFilters());
    }

    /**
     * The filters should be sent as a `filter` query param, keyed as given, with their values made encodable (a
     * Carbon date as `Y-m-d H:i:s`).
     */
    public function test_query_case_1(): void
    {
        $paramBag = new FiltersParamBagFixture;
        $paramBag->withFilters(['createdAfter' => Carbon::parse('2024-05-12 16:12:56'), 'is_active' => true]);

        $guzzleParams = $paramBag->asGuzzleParams();

        $this->assertSame(['filter' => ['createdAfter' => '2024-05-12 16:12:56', 'is_active' => true]], $guzzleParams['query']);
    }

    public function test_get_filters_validation_rules_case_1(): void
    {
        $validationRules = (new FiltersParamBagFixture)->getFiltersValidationRules();

        $this->assertSame(['filter' => ['array']], $validationRules);
    }
}
