<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductOfferDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsProductDtos
{
    protected function mockProductOfferApiData(array $override = []): array
    {
        $baseData = [
            'id'            => TestCase::unique(),
            'product_id'    => TestCase::unique(),
            'seller_id'     => TestCase::unique(),
            'price_tax_exc' => '62.500000',
            'price_tax_inc' => '75.000000',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockProductOfferDto(array $override = []): ProductOfferDto
    {
        return new ProductOfferDto(Obj::fromArray($this->mockProductOfferApiData($override)));
    }

    protected function mockProductApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'reference'    => 'HAIRCUT',
            'translations' => ['data' => [$this->mockProductTranslationApiData()]],
        ];

        return [...$baseData, ...$override];
    }

    protected function mockProductTranslationApiData(array $override = []): array
    {
        $baseData = [
            'locale'      => 'en',
            'language_id' => 1,
            'default'     => true,
            'name'        => 'Haircut',
        ];

        return [...$baseData, ...$override];
    }
}
