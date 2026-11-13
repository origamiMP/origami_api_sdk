<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceAvailabilityReviewDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewDtoNotConstructableException;

/**
 * An availability added by a provider to a planning attached to a place, which the team approves before it can be booked,
 * or a presence the provider asks for, without planning. Exactly one of `rule`, `exception`, `slot` and `presence` is
 * set.
 */
class ServiceAvailabilityReviewDto extends ApiResponseDto
{
    public int $id;

    public ?int $servicePlanningId;

    public ?int $sellerId;

    public ?ServiceAvailabilityReviewRuleDto $rule;

    public ?ServiceAvailabilityReviewExceptionDto $exception;

    public ?ServiceAvailabilityReviewSlotDto $slot;

    public ?ServicePresenceDto $presence;

    public ServiceAvailabilityReviewDtoStatusEnum $status;

    public Carbon $requestedAt;

    public ?Carbon $reviewedAt;

    public ?string $note;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                  => 'id',
            'service_planning_id' => 'servicePlanningId',
            'seller_id'           => 'sellerId',
            'rule'                => fn (?object $rule) => $this->rule = is_null($rule) ? null : new ServiceAvailabilityReviewRuleDto($rule),
            'exception'           => fn (?object $exception) => $this->exception = is_null($exception) ? null : new ServiceAvailabilityReviewExceptionDto($exception),
            'slot'                => fn (?object $slot) => $this->slot = is_null($slot) ? null : new ServiceAvailabilityReviewSlotDto($slot),
            'presence'            => fn (?object $presence) => $this->presence = is_null($presence) ? null : new ServicePresenceDto($presence),
            'status'              => fn ($status) => $this->status = ServiceAvailabilityReviewDtoStatusEnum::from($status),
            'requested_at'        => fn (string $requestedAt) => $this->requestedAt = Carbon::parse($requestedAt),
            'reviewed_at'         => fn (?string $reviewedAt) => $this->reviewedAt = is_null($reviewedAt) ? null : Carbon::parse($reviewedAt),
            'note'                => 'note',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceAvailabilityReviewDtoNotConstructableException($msg, previous: $previous);
    }
}
