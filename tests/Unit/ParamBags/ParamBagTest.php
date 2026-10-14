<?php

namespace Tests\Unit\ParamBags;

use Carbon\Carbon;
use Tests\Fixtures\JsonParamBagFixture;
use Tests\TestCase;

/**
 * Behavior shared by every param bag, tested through a fixture (which adds nothing to it).
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
}
