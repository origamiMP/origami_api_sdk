<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuotePriceBuildUpRowDtoTypeEnum;
use Tests\TestCase;

trait TestsB2BAdvancedDtos
{
    /**
     * A quote the seller has not answered yet, its price build-up at 0.
     */
    protected function mockQuoteResponseApiData(array $override = []): array
    {
        $baseData = [
            'order_seller_id'   => TestCase::unique(),
            'has_responded'     => false,
            'price_tax_exc'     => null,
            'delay_days'        => null,
            'conditions'        => null,
            'responded_at'      => null,
            'is_selected'       => false,
            'is_converted'      => false,
            'is_closed'         => false,
            'status'            => 'to_answer',
            'refusal_reason'    => null,
            'engagement_months' => null,
            'price_build_up'    => [$this->mockQuotePriceBuildUpRowApiData()],
        ];

        return array_merge($baseData, $override);
    }

    /**
     * An amount driver: a rate one adds `quantity` and `unit_price`.
     */
    protected function mockQuotePriceBuildUpRowApiData(array $override = []): array
    {
        $baseData = [
            'poste'   => 'Margin',
            'unit'    => '€ HT',
            'type'    => QuotePriceBuildUpRowDtoTypeEnum::AMOUNT->value,
            'montant' => 0,
        ];

        return array_merge($baseData, $override);
    }
}
