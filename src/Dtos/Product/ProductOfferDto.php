<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Product;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductOfferDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class ProductOfferDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public int $productId;

    public int $sellerId;

    public float $priceTaxExc;

    public float $priceTaxInc;

    /** May be undefined if the corresponding data was not included. */
    public ProductDto $product;

    public static function getAvailableIncludes(): array
    {
        return [
            'product' => ProductDto::class,
            // 'seller',
            // 'shipping_offers',
            // 'tax',
            // 'specific_price_rules',
            // 'specific_price_rules_exists',
            // 'catalog_price_rules_exists',
            // 'product_variant',
            // 'offer_info',
            // 'additional_costs',
            // 'warehouse',
            // 'association_groups',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'            => 'id',
            'product_id'    => 'productId',
            'seller_id'     => 'sellerId',
            'price_tax_exc' => fn ($price) => $this->priceTaxExc = (float) $price,
            'price_tax_inc' => fn ($price) => $this->priceTaxInc = (float) $price,
            'product'       => fn (object $product) => $this->product = $this->makeIncluded($product, ProductDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ProductOfferDtoNotConstructableException($msg, previous: $previous);
    }
}
