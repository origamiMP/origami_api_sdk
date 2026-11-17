<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class RescheduleServiceBookingRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public int $serviceSlotId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSlotId' => 'service_slot_id',
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
