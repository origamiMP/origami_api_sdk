<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced;

use OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced\RespondQuoteResponse\RespondQuoteResponseCostBreakdownRowParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * The offer of a seller. When the quote has cost drivers, the API checks that their amounts add up to the price.
 */
class RespondQuoteResponseRequestParamBag extends RequestParamBag
{
    public float $priceTaxExc;

    public ?int $delayDays;

    public ?string $conditions;

    public ?int $engagementMonths;

    public ?bool $onCall247;

    public ?int $rseScore;

    public ?int $referencesCount;

    /** @var array<int, string> */
    public array $certifications;

    /** @var array<int, RespondQuoteResponseCostBreakdownRowParamBag> */
    public array $costBreakdown;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'priceTaxExc'      => 'price_tax_exc',
            'delayDays'        => 'delay_days',
            'conditions'       => 'conditions',
            'engagementMonths' => 'engagement_months',
            'onCall247'        => 'on_call_247',
            'rseScore'         => 'rse_score',
            'referencesCount'  => 'references_count',
            'certifications'   => 'certifications',
            'costBreakdown'    => 'cost_breakdown',
        ];
    }
}
