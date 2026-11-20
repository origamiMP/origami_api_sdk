<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalDtoNotConstructableException;

/**
 * What a provider withdrew shortly before it starts (less than the threshold of the module): a range of its presence at a place, or a free slot of a planning. Its times are wall-clock times of the place.
 */
class ServiceLateWithdrawalDto extends ApiResponseDto
{
    public int $id;

    public int $sellerId;

    public ?int $serviceSiteId;

    /** The planning of a slot, none for a range of a presence */
    public ?int $servicePlanningId;

    /** The presence of a range, none for a slot */
    public ?int $servicePresenceId = null;

    public ?int $serviceResourceId;

    public int $durationMinutes;

    public int $noticeMinutes;

    public int $thresholdMinutes;

    public Carbon $startsAt;

    public Carbon $endsAt;

    public Carbon $withdrawnAt;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                  => 'id',
            'seller_id'           => 'sellerId',
            'service_site_id'     => 'serviceSiteId',
            'service_planning_id' => 'servicePlanningId',
            'service_presence_id' => 'servicePresenceId',
            'service_resource_id' => 'serviceResourceId',
            'duration_minutes'    => 'durationMinutes',
            'notice_minutes'      => 'noticeMinutes',
            'threshold_minutes'   => 'thresholdMinutes',
            'starts_at'           => fn (string $startsAt) => $this->startsAt = Carbon::parse($startsAt),
            'ends_at'             => fn (string $endsAt) => $this->endsAt = Carbon::parse($endsAt),
            'withdrawn_at'        => fn (string $withdrawnAt) => $this->withdrawnAt = Carbon::parse($withdrawnAt),
        ];
    }

    protected function getOptionalKeys(): array
    {
        return [
            'service_presence_id',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceLateWithdrawalDtoNotConstructableException($msg, previous: $previous);
    }
}
