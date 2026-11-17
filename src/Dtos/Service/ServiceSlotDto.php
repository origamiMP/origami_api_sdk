<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceSlotDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceSlotDtoNotConstructableException;

class ServiceSlotDto extends ApiResponseDto
{
    public int $id;

    public int $servicePlanningId;

    public Carbon $startsAt;

    public Carbon $endsAt;

    public int $capacity;

    public int $bookedCount;

    public ServiceSlotDtoStatusEnum $status;

    public ?string $closedReason;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                  => 'id',
            'service_planning_id' => 'servicePlanningId',
            'starts_at'           => fn (string $startsAt) => $this->startsAt = Carbon::parse($startsAt),
            'ends_at'             => fn (string $endsAt) => $this->endsAt = Carbon::parse($endsAt),
            'capacity'            => 'capacity',
            'booked_count'        => 'bookedCount',
            'status'              => fn ($status) => $this->status = ServiceSlotDtoStatusEnum::from($status),
            'closed_reason'       => 'closedReason',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSlotDtoNotConstructableException($msg, previous: $previous);
    }
}
