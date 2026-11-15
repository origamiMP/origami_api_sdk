<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceSiteBoardSlotDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardSlotDtoNotConstructableException;

/**
 * A slot of a provider of the place, with the resource it takes and its bookings. Its times are wall-clock times of the place.
 */
class ServiceSiteBoardSlotDto extends ApiResponseDto
{
    public int $id;

    public int $servicePlanningId;

    public int $sellerId;

    public ?int $serviceResourceId;

    public Carbon $startsAt;

    public Carbon $endsAt;

    public ServiceSiteBoardSlotDtoStatusEnum $status;

    public ?string $closedReason;

    public int $capacity;

    public int $bookedCount;

    /** @var Collection<int, ServiceSiteBoardBookingDto> */
    public Collection $bookings;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                  => 'id',
            'service_planning_id' => 'servicePlanningId',
            'seller_id'           => 'sellerId',
            'service_resource_id' => 'serviceResourceId',
            'starts_at'           => fn (string $startsAt) => $this->startsAt = Carbon::parse($startsAt),
            'ends_at'             => fn (string $endsAt) => $this->endsAt = Carbon::parse($endsAt),
            'status'              => fn ($status) => $this->status = ServiceSiteBoardSlotDtoStatusEnum::from($status),
            'closed_reason'       => 'closedReason',
            'capacity'            => 'capacity',
            'booked_count'        => 'bookedCount',
            'bookings'            => fn (array $bookings) => $this->initBookings($bookings),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteBoardSlotDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initBookings(array $bookings): void
    {
        $this->bookings = collect($bookings)->map(fn (object $booking) => new ServiceSiteBoardBookingDto($booking));
    }
}
