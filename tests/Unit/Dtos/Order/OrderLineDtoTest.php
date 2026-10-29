<?php

namespace Tests\Unit\Dtos\Order;

use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Order\OrderLineDtoNotConstructableException;
use Tests\TestCase;

class OrderLineDtoTest extends TestCase
{
    /**
     * A line read while the form_builder option is off, or without answer, should leave its form submission undefined.
     */
    public function test_form_submission_case_1(): void
    {
        $line = $this->mockOrderLineDto();

        $this->assertFalse(isset($line->formSubmission));
    }

    /**
     * A malformed form submission include should be rejected.
     */
    public function test_form_submission_case_2(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->mockOrderLineDto(['form_submission' => ['id' => 42]]),
            OrderLineDtoNotConstructableException::class,
        );
    }
}
