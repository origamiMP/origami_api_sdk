<?php

namespace Tests\Unit\Traits\Dtos;

use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\Fixtures\TimestampsDtoFixture;
use Tests\TestCase;

/**
 * Behavior shared by every Dto with timestamps, tested through a fixture (which adds nothing to it).
 */
class HasTimestampsTest extends TestCase
{
    /**
     * A null created_at should make the Dto not constructable, instead of giving the current date.
     */
    public function test_created_at_case_1(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->makeFixture(['created_at' => null, 'updated_at' => '2024-05-12 16:12:56']),
            ApiResponseDtoNotConstructableException::class,
            "Could not assign data 'created_at'",
        );
    }

    private function makeFixture(array $apiData): TimestampsDtoFixture
    {
        return new TimestampsDtoFixture(Obj::fromArray($apiData));
    }
}
