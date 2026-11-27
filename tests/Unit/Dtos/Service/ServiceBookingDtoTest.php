<?php

namespace Tests\Unit\Dtos\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceSlotDto;
use Tests\TestCase;

class ServiceBookingDtoTest extends TestCase
{
    /**
     * A booking without slot, offer, customer, seller, state nor order: the API leaves out an include without item, so
     * they should be null.
     */
    public function test_includes_case_1(): void
    {
        $booking = $this->mockServiceBookingDto();

        $this->assertNull($booking->slot);
        $this->assertNull($booking->offer);
        $this->assertNull($booking->customer);
        $this->assertNull($booking->seller);
        $this->assertNull($booking->state);
        $this->assertNull($booking->order);
        $this->assertFalse(isset($booking->rangeSlots));
    }

    /**
     * The order of a booking from an API that does not send the order it belongs to yet should still be read.
     */
    public function test_order_case_1(): void
    {
        $booking = $this->mockServiceBookingDto(['order' => ['data' => ['id' => 5, 'reference' => 'ORD-0005']]]);

        $this->assertSame(5, $booking->order->id);
        $this->assertNull($booking->order->orderId);
    }

    /**
     * The slots of a booking over a range of days should be read.
     */
    public function test_range_slots_case_1(): void
    {
        $booking = $this->mockServiceBookingDto(['rangeSlots' => ['data' => [$this->mockServiceSlotApiData(['id' => 7]), $this->mockServiceSlotApiData(['id' => 8])]]]);

        $this->assertContainsOnlyInstancesOf(ServiceSlotDto::class, $booking->rangeSlots);
        $this->assertSame([7, 8], $booking->rangeSlots->pluck('id')->all());
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
