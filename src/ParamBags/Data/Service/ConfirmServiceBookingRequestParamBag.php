<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

/**
 * The order line and the reference of the storefront order, when there is one: the operator confirms a booking without.
 */
class ConfirmServiceBookingRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public ?int $orderLineId;

    public ?string $reference;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'orderLineId' => 'order_line_id',
            'reference'   => 'reference',
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
