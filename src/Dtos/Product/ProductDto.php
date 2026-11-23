<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Product;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class ProductDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public ?string $reference;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, ProductTranslationDto>
     */
    public Collection $translations;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => ProductTranslationDto::class,
            // 'variants',
            // 'default_variant',
            // 'images',
            // 'categories',
            // 'product_reviews',
            // 'features',
            // 'default_category',
            // 'product_state',
            // 'state_logs',
            // 'related_products',
            // 'pack_products',
            // 'visibilities',
            // 'families',
            // 'customizations',
            // 'final_categories',
            // 'offers',
            // 'seller_owner',
            // 'purchase_form',
            // 'quote_form',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'reference'    => 'reference',
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, ProductTranslationDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ProductDtoNotConstructableException($msg, previous: $previous);
    }
}
