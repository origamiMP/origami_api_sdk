<?php

namespace Tests\Unit\Dtos\Product;

use Tests\TestCase;

class ProductOfferDtoTest extends TestCase
{
    /**
     * The prices of an offer should be read as floats.
     */
    public function test_prices_case_1(): void
    {
        $offer = $this->mockProductOfferDto();

        $this->assertSame(62.5, $offer->priceTaxExc);
        $this->assertSame(75.0, $offer->priceTaxInc);
    }

    /**
     * A price the API sends as null should stay null, not become 0.
     */
    public function test_prices_case_2(): void
    {
        $offer = $this->mockProductOfferDto(['price_tax_exc' => null, 'price_tax_inc' => null]);

        $this->assertNull($offer->priceTaxExc);
        $this->assertNull($offer->priceTaxInc);
    }
}
