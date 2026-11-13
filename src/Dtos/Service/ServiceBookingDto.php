<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductOfferDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoOriginEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class ServiceBookingDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public ?int $serviceSlotId;

    public int $productOfferId;

    public ?int $orderLineId;

    public ?int $userId;

    public ?string $reference;

    public int $quantity;

    public ?Carbon $startsOn;

    public ?Carbon $endsOn;

    public ServiceBookingDtoStatusEnum $status;

    public ?int $stateId;

    public ?string $noShowFeeAmount;

    public ?ServiceBookingDtoOriginEnum $origin;

    public ?string $originDetail;

    public ?string $lateCancellationFeeAmount;

    public ?Carbon $holdExpiresAt;

    /** May be undefined if the corresponding data was not included. */
    public ServiceSlotDto $slot;

    /** May be undefined if the corresponding data was not included. */
    public ProductOfferDto $offer;

    /**
     * May be undefined if the corresponding data was not included. Null when the current user may not see it, or the
     * booking has none.
     */
    public ?ServiceBookingCustomerDto $customer;

    /** May be undefined if the corresponding data was not included. Null when the booking has none. */
    public ?ServiceBookingSellerDto $seller;

    /** May be undefined if the corresponding data was not included. Null when the booking has none. */
    public ?StateDto $state;

    /**
     * The states the current user can move the booking to. May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, StateDto>
     */
    public Collection $availableStates;

    /** May be undefined if the corresponding data was not included. Null when the booking has none. */
    public ?ServiceBookingOrderDto $order;

    public static function getAvailableIncludes(): array
    {
        return [
            'slot'  => ServiceSlotDto::class,
            'offer' => ProductOfferDto::class,
            // 'rangeSlots',
            'customer'         => ServiceBookingCustomerDto::class,
            'seller'           => ServiceBookingSellerDto::class,
            'state'            => StateDto::class,
            'available_states' => StateDto::class,
            'order'            => ServiceBookingOrderDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                           => 'id',
            'service_slot_id'              => 'serviceSlotId',
            'product_offer_id'             => 'productOfferId',
            'order_line_id'                => 'orderLineId',
            'user_id'                      => 'userId',
            'reference'                    => 'reference',
            'quantity'                     => 'quantity',
            'starts_on'                    => fn (?string $startsOn) => $this->startsOn = is_null($startsOn) ? null : Carbon::parse($startsOn),
            'ends_on'                      => fn (?string $endsOn) => $this->endsOn = is_null($endsOn) ? null : Carbon::parse($endsOn),
            'status'                       => fn ($status) => $this->status = ServiceBookingDtoStatusEnum::from($status),
            'state_id'                     => 'stateId',
            'no_show_fee_amount'           => 'noShowFeeAmount',
            'origin'                       => fn (?string $origin) => $this->origin = is_null($origin) ? null : ServiceBookingDtoOriginEnum::from($origin),
            'origin_detail'                => 'originDetail',
            'late_cancellation_fee_amount' => 'lateCancellationFeeAmount',
            'hold_expires_at'              => fn (?string $holdExpiresAt) => $this->holdExpiresAt = is_null($holdExpiresAt) ? null : Carbon::parse($holdExpiresAt),
            ...$this->getTimestampsAsDataStructureToProperties(),
            'slot'             => fn (object $slot) => $this->slot = $this->makeIncluded($slot, ServiceSlotDto::class),
            'offer'            => fn (object $offer) => $this->offer = $this->makeIncluded($offer, ProductOfferDto::class),
            'customer'         => fn (object $customer) => $this->customer = $this->makeNullableIncluded($customer, ServiceBookingCustomerDto::class),
            'seller'           => fn (object $seller) => $this->seller = $this->makeNullableIncluded($seller, ServiceBookingSellerDto::class),
            'state'            => fn (object $state) => $this->state = $this->makeNullableIncluded($state, StateDto::class),
            'available_states' => fn (object $states) => $this->availableStates = $this->collectIncluded($states, StateDto::class),
            'order'            => fn (object $order) => $this->order = $this->makeNullableIncluded($order, ServiceBookingOrderDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceBookingDtoNotConstructableException($msg, previous: $previous);
    }
}
