<?php

namespace Tests\Unit\Dtos\Service;

use Tests\TestCase;

class ServiceBookingDtoTest extends TestCase
{
    /**
     * A booking held in a cart has no customer, order nor state yet: their includes should be null.
     */
    public function test_includes_case_1(): void
    {
        $booking = $this->mockServiceBookingDto(['customer' => ['data' => []], 'order' => ['data' => []], 'state' => ['data' => []]]);

        $this->assertNull($booking->customer);
        $this->assertNull($booking->order);
        $this->assertNull($booking->state);
    }

    /**
     * The customer should keep its email and the one of its user null when the current user may not see them.
     */
    public function test_customer_case_1(): void
    {
        $booking = $this->mockServiceBookingDto(['customer' => ['data' => $this->mockServiceBookingCustomerApiData([
            'email' => null,
            'user'  => ['id' => 4, 'name' => 'Jane Doe', 'email' => null],
        ])]]);

        $this->assertNull($booking->customer->email);
        $this->assertNull($booking->customer->user->email);
        $this->assertSame('Jane Doe', $booking->customer->user->name);
    }
}
