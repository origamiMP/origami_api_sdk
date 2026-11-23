<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalDtoNotConstructableException;

/**
 * A slot withdrawn by its provider shortly before it starts (less than the threshold of the module). Its times are wall-clock times of the planning.
 */
class ServiceLateWithdrawalDto extends ApiResponseDto
{
    public int $id;

    public int $sellerId;

    public ?int $serviceSiteId;

    public int $servicePlanningId;

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
            'service_resource_id' => 'serviceResourceId',
            'duration_minutes'    => 'durationMinutes',
            'notice_minutes'      => 'noticeMinutes',
            'threshold_minutes'   => 'thresholdMinutes',
            'starts_at'           => fn (string $startsAt) => $this->startsAt = Carbon::parse($startsAt),
            'ends_at'             => fn (string $endsAt) => $this->endsAt = Carbon::parse($endsAt),
            'withdrawn_at'        => fn (string $withdrawnAt) => $this->withdrawnAt = Carbon::parse($withdrawnAt),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceLateWithdrawalDtoNotConstructableException($msg, previous: $previous);
    }
}
