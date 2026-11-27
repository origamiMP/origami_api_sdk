<?php

namespace Tests\Unit\Dtos\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServicePlanningDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServicePlanningDtoBookingUnitEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The API books a planning by time slot, by day or over a range of days: a planning of any of them should be read, so
 * that one of them does not make the whole list of plannings unreadable.
 */
class ServicePlanningDtoTest extends TestCase
{
    #[DataProvider('bookingUnitProvider')]
    public function test_booking_unit_case_1(string $bookingUnit, ServicePlanningDtoBookingUnitEnum $expected): void
    {
        $planning = new ServicePlanningDto(Obj::fromArray($this->mockServicePlanningApiData(['booking_unit' => $bookingUnit])));

        $this->assertSame($expected, $planning->bookingUnit);
    }

    public static function bookingUnitProvider(): array
    {
        return [
            'by time slot'         => ['time_slot', ServicePlanningDtoBookingUnitEnum::TIME_SLOT],
            'by day'               => ['day', ServicePlanningDtoBookingUnitEnum::DAY],
            'over a range of days' => ['day_range', ServicePlanningDtoBookingUnitEnum::DAY_RANGE],
        ];
    }

    /**
     * Whether the offer of a planning is active should be read, and be null from an API that does not send it yet.
     */
    public function test_offer_is_active_case_1(): void
    {
        $data = $this->mockServicePlanningApiData(['offer_is_active' => false]);
        $planning = new ServicePlanningDto(Obj::fromArray($data));
        unset($data['offer_is_active']);
        $older = new ServicePlanningDto(Obj::fromArray($data));

        $this->assertFalse($planning->offerIsActive);
        $this->assertNull($older->offerIsActive);
    }
}
