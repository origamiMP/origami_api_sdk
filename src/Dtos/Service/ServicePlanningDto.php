<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Service;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductOfferDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServicePlanningDtoBookingUnitEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServicePlanningDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class ServicePlanningDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public int $productOfferId;

    /** Null when the offer of the planning is gone. */
    public ?int $productId;

    /** Null when the offer of the planning is gone. */
    public ?int $sellerId;

    public string $name;

    public ServicePlanningDtoBookingUnitEnum $bookingUnit;

    public ?int $slotDurationMinutes;

    public int $capacity;

    public string $timezone;

    public bool $isActive;

    public int $position;

    /** May be undefined if the corresponding data was not included. */
    public ProductOfferDto $offer;

    /** May be undefined if the corresponding data was not included. Null when the offer of the planning is gone. */
    public ?ProductDto $product;

    /** May be undefined if the corresponding data was not included. Null when the offer of the planning is gone. */
    public ?SellerDto $seller;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, ServiceSlotDto>
     */
    public Collection $slots;

    public static function getAvailableIncludes(): array
    {
        return [
            'offer'   => ProductOfferDto::class,
            'product' => ProductDto::class,
            'seller'  => SellerDto::class,
            // 'rules',
            // 'exceptions',
            'slots' => ServiceSlotDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                    => 'id',
            'product_offer_id'      => 'productOfferId',
            'product_id'            => 'productId',
            'seller_id'             => 'sellerId',
            'name'                  => 'name',
            'booking_unit'          => fn ($bookingUnit) => $this->bookingUnit = ServicePlanningDtoBookingUnitEnum::from($bookingUnit),
            'slot_duration_minutes' => 'slotDurationMinutes',
            'capacity'              => 'capacity',
            'timezone'              => 'timezone',
            'is_active'             => 'isActive',
            'position'              => 'position',
            'offer'                 => fn (object $offer) => $this->offer = $this->makeIncluded($offer, ProductOfferDto::class),
            'product'               => fn (object $product) => $this->product = $this->makeNullableIncluded($product, ProductDto::class),
            'seller'                => fn (object $seller) => $this->seller = $this->makeNullableIncluded($seller, SellerDto::class),
            'slots'                 => fn (object $slots) => $this->slots = $this->collectIncluded($slots, ServiceSlotDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServicePlanningDtoNotConstructableException($msg, previous: $previous);
    }
}
