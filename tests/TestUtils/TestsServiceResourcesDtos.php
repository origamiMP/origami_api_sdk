<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceSlotDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceAvailabilityReviewDtoStatusEnum;
use Tests\TestCase;

trait TestsServiceResourcesDtos
{
    protected function mockServiceSiteApiData(array $override = []): array
    {
        $baseData = [
            'id'            => TestCase::unique(),
            'name'          => 'Marais',
            'phone'         => null,
            'email'         => null,
            'timezone'      => 'Europe/Paris',
            'is_active'     => true,
            'address'       => $this->mockAddressApiData(),
            'opening_hours' => [['weekday' => 1, 'opens_at' => '09:00', 'closes_at' => '19:00']],
            'seller_ids'    => [TestCase::unique()],
            'resources'     => [$this->mockServiceResourceApiData()],
            'plannings'     => [['id' => TestCase::unique(), 'resource_type' => 'chair', 'buffer_before_minutes' => 0, 'buffer_after_minutes' => 10]],
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServiceResourceApiData(array $override = []): array
    {
        $baseData = [
            'id'              => TestCase::unique(),
            'service_site_id' => TestCase::unique(),
            'type'            => 'chair',
            'name'            => 'Chair 1',
            'is_active'       => true,
            'position'        => 0,
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServiceSiteEventApiData(array $override = []): array
    {
        $baseData = [
            'id'              => TestCase::unique(),
            'service_site_id' => TestCase::unique(),
            'label'           => 'Works',
            'starts_at'       => '2024-05-12 09:00:00',
            'ends_at'         => '2024-05-12 13:00:00',
            'all_resources'   => true,
            'resource_ids'    => [],
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServiceSiteBoardApiData(array $override = []): array
    {
        $baseData = [
            'site'      => ['id' => TestCase::unique(), 'name' => 'Marais', 'timezone' => 'Europe/Paris'],
            'resources' => [['id' => TestCase::unique(), 'type' => 'chair', 'name' => 'Chair 1', 'is_active' => true]],
            'slots'     => [[
                'id'                  => TestCase::unique(),
                'service_planning_id' => TestCase::unique(),
                'seller_id'           => TestCase::unique(),
                'service_resource_id' => TestCase::unique(),
                'starts_at'           => '2024-05-12 10:00:00',
                'ends_at'             => '2024-05-12 11:00:00',
                'status'              => ServiceSlotDtoStatusEnum::OPEN->value,
                'closed_reason'       => null,
                'capacity'            => 1,
                'booked_count'        => 1,
                'bookings'            => [['id' => TestCase::unique(), 'status' => ServiceBookingDtoStatusEnum::CONFIRMED->value, 'state_id' => TestCase::unique(), 'quantity' => 1]],
            ]],
            'events' => [],
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServiceAvailabilityReviewApiData(array $override = []): array
    {
        $baseData = [
            'id'                  => TestCase::unique(),
            'service_planning_id' => TestCase::unique(),
            'seller_id'           => TestCase::unique(),
            'slot'                => ['id' => TestCase::unique(), 'starts_at' => '2024-05-12 10:00:00', 'ends_at' => '2024-05-12 11:00:00'],
            'presence'            => null,
            'status'              => ServiceAvailabilityReviewDtoStatusEnum::PENDING->value,
            'requested_at'        => '2024-05-12 16:12:56',
            'reviewed_at'         => null,
            'note'                => null,
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServicePresenceApiData(array $override = []): array
    {
        $baseData = [
            'id'                  => TestCase::unique(),
            'service_site_id'     => TestCase::unique(),
            'service_resource_id' => 3,
            'resource'            => ['id' => 3, 'type' => 'chair', 'name' => 'Chair 3'],
            'seller_id'           => TestCase::unique(),
            'series_id'           => null,
            'starts_at'           => '2024-05-18 09:00:00',
            'ends_at'             => '2024-05-18 18:00:00',
            'requested_by_seller' => false,
            'status'              => ServiceAvailabilityReviewDtoStatusEnum::APPROVED->value,
            'review_id'           => null,
            'note'                => null,
        ];

        return [...$baseData, ...$override];
    }

    /**
     * The presence a review is about, as the API sends it inside the review.
     */
    protected function mockServiceAvailabilityReviewPresenceApiData(array $override = []): array
    {
        $baseData = [
            'id'              => TestCase::unique(),
            'service_site_id' => TestCase::unique(),
            'seller_id'       => TestCase::unique(),
            'series_id'       => null,
            'starts_at'       => '2024-05-18 09:00:00',
            'ends_at'         => '2024-05-18 18:00:00',
            'resource'        => ['id' => 3, 'type' => 'chair', 'name' => 'Chair 3'],
        ];

        return [...$baseData, ...$override];
    }

    protected function mockServiceLateWithdrawalApiData(array $override = []): array
    {
        $baseData = [
            'id'                  => TestCase::unique(),
            'seller_id'           => TestCase::unique(),
            'service_site_id'     => TestCase::unique(),
            'service_planning_id' => TestCase::unique(),
            'service_resource_id' => null,
            'duration_minutes'    => 60,
            'notice_minutes'      => 120,
            'threshold_minutes'   => 2880,
            'starts_at'           => '2024-05-12 10:00:00',
            'ends_at'             => '2024-05-12 11:00:00',
            'withdrawn_at'        => '2024-05-12 08:00:00',
        ];

        return [...$baseData, ...$override];
    }
}
