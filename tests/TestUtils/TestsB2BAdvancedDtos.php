<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuoteCostDriverDtoTypeEnum;
use Tests\TestCase;

trait TestsB2BAdvancedDtos
{
    /**
     * A quote the seller has not answered yet: no price, the cost drivers of its category at 0.
     */
    protected function mockQuoteResponseApiData(array $override = []): array
    {
        $baseData = [
            'order_seller_id'        => TestCase::unique(),
            'reference'              => 'WZXIIYTYX',
            'seller_id'              => TestCase::unique(),
            'seller_name'            => 'Clean & Co',
            'has_responded'          => false,
            'price_tax_exc'          => null,
            'previous_price_tax_exc' => null,
            'changed_fields'         => [],
            'delay_days'             => null,
            'conditions'             => null,
            'responded_at'           => null,
            'is_selected'            => false,
            'selected_at'            => null,
            'is_converted'           => false,
            'refusal_reason'         => null,
            'engagement_months'      => null,
            'on_call_247'            => false,
            'rse_score'              => null,
            'references_count'       => null,
            'certifications'         => [],
            'cost_breakdown'         => [],
            'cost_drivers'           => [$this->mockQuoteCostDriverApiData()],
            'price_build_up'         => [$this->mockQuotePriceBuildUpRowApiData()],
            'price_build_up_total'   => 0.0,
        ];

        return array_merge($baseData, $override);
    }

    protected function mockQuoteCostDriverApiData(array $override = []): array
    {
        $baseData = [
            'label' => 'Cleaning hours',
            'unit'  => 'h',
            'type'  => QuoteCostDriverDtoTypeEnum::RATE->value,
        ];

        return array_merge($baseData, $override);
    }

    /**
     * A rate driver: an amount driver has no `quantity` nor `unit_price`.
     */
    protected function mockQuotePriceBuildUpRowApiData(array $override = []): array
    {
        $baseData = [
            'poste'      => 'Cleaning hours',
            'unit'       => 'h',
            'type'       => QuoteCostDriverDtoTypeEnum::RATE->value,
            'quantity'   => 0,
            'unit_price' => 0,
            'montant'    => 0,
        ];

        return array_merge($baseData, $override);
    }
}
