<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoOriginEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServicePlanningDtoBookingUnitEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceSlotDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsServiceDtos
{
    protected function mockServiceBookingApiData(array $override = []): array
    {
        $baseData = [
            'id'                           => TestCase::unique(),
            'service_slot_id'              => TestCase::unique(),
            'product_offer_id'             => TestCase::unique(),
            'order_line_id'                => TestCase::unique(),
            'user_id'                      => TestCase::unique(),
            'reference'                    => 'RES-0001',
            'quantity'                     => 1,
            'starts_on'                    => null,
            'ends_on'                      => null,
            'status'                       => ServiceBookingDtoStatusEnum::CONFIRMED->value,
            'state_id'                     => TestCase::unique(),
            'no_show_fee_amount'           => null,
            'origin'                       => ServiceBookingDtoOriginEnum::MARKETPLACE->value,
            'origin_detail'                => null,
            'late_cancellation_fee_amount' => null,
            'hold_expires_at'              => null,
            'created_at'                   => '2024-05-12T16:12:56.000000Z',
            'updated_at'                   => '2024-05-12T16:12:56.000000Z',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServiceBookingDto(array $override = []): ServiceBookingDto
    {
        return new ServiceBookingDto(Obj::fromArray($this->mockServiceBookingApiData($override)));
    }

    protected function mockServiceSlotApiData(array $override = []): array
    {
        $baseData = [
            'id'                  => TestCase::unique(),
            'service_planning_id' => TestCase::unique(),
            'starts_at'           => '2024-05-12 10:00:00',
            'ends_at'             => '2024-05-12 11:00:00',
            'capacity'            => 1,
            'booked_count'        => 1,
            'remaining_capacity'  => 0,
            'status'              => ServiceSlotDtoStatusEnum::OPEN->value,
            'closed_reason'       => null,
            'closed_by'           => null,
            'closed_at'           => null,
            'origin'              => 'rule',
            'source_rule_id'      => TestCase::unique(),
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServicePlanningApiData(array $override = []): array
    {
        $baseData = [
            'id'                          => TestCase::unique(),
            'product_offer_id'            => TestCase::unique(),
            'product_id'                  => TestCase::unique(),
            'seller_id'                   => TestCase::unique(),
            'name'                        => 'Haircut',
            'booking_unit'                => ServicePlanningDtoBookingUnitEnum::TIME_SLOT->value,
            'slot_duration_minutes'       => 45,
            'capacity'                    => 1,
            'min_days'                    => null,
            'max_days'                    => null,
            'timezone'                    => 'Europe/Paris',
            'min_notice_minutes'          => null,
            'max_horizon_days'            => 60,
            'no_show_fee_type'            => null,
            'no_show_fee_value'           => null,
            'free_cancellation_hours'     => null,
            'late_cancellation_fee_type'  => null,
            'late_cancellation_fee_value' => null,
            'is_active'                   => true,
            'position'                    => 0,
            'slots_count'                 => null,
            'created_at'                  => '2024-05-12T16:12:56.000000Z',
            'updated_at'                  => '2024-05-12T16:12:56.000000Z',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServiceBookingCustomerApiData(array $override = []): array
    {
        $baseData = [
            'id'    => TestCase::unique(),
            'name'  => 'John Doe',
            'email' => 'john@doe.com',
            'phone' => '+33612345678',
            'user'  => ['id' => TestCase::unique(), 'name' => 'Jane Doe', 'email' => 'jane@doe.com'],
        ];

        return [...$baseData, ...$override];
    }

    protected function mockStateApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'key'          => 'service_booking_confirmed',
            'color'        => '#2e7d32',
            'initial'      => false,
            'is_final'     => false,
            'readonly'     => true,
            'workflow_id'  => TestCase::unique(),
            'text_color'   => '#ffffff',
            'name'         => 'Confirmed',
            'action_name'  => null,
            'description'  => null,
            'translations' => ['data' => []],
        ];

        return [...$baseData, ...$override];
    }
}
