<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoOriginEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

/**
 * A slot of a planning by time slot (`serviceSlotId` and `productOfferId`), or the days of a planning by day range
 * (`servicePlanningId`, `startsOn` and `endsOn`). The operator books for a customer through her user (`userId`), a
 * seller cannot.
 */
class CreateServiceBookingRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public int $serviceSlotId;

    public int $productOfferId;

    public int $servicePlanningId;

    public Carbon $startsOn;

    public Carbon $endsOn;

    public int $quantity;

    public ?int $userId;

    public ?string $reference;

    public int $holdDurationMinutes;

    public ServiceBookingDtoOriginEnum $origin;

    public ?string $originDetail;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSlotId'       => 'service_slot_id',
            'productOfferId'      => 'product_offer_id',
            'servicePlanningId'   => 'service_planning_id',
            'startsOn'            => fn (Carbon $startsOn) => ['starts_on' => $startsOn->format('Y-m-d')],
            'endsOn'              => fn (Carbon $endsOn) => ['ends_on' => $endsOn->format('Y-m-d')],
            'quantity'            => 'quantity',
            'userId'              => 'user_id',
            'reference'           => 'reference',
            'holdDurationMinutes' => 'hold_duration_minutes',
            'origin'              => 'origin',
            'originDetail'        => 'origin_detail',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludesParamsList();
    }

    protected static function getRequestMainDto(): string
    {
        return ServiceBookingDto::class;
    }
}
