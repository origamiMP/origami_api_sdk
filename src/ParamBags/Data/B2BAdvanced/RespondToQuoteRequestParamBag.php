<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * The offer of a seller. When the quote has cost drivers, the API checks that their amounts add up to the price.
 */
class RespondToQuoteRequestParamBag extends RequestParamBag
{
    public float $priceTaxExc;

    public ?int $delayDays;

    public ?string $conditions;

    public ?int $engagementMonths;

    public ?bool $onCall247;

    public ?int $rseScore;

    public ?int $referencesCount;

    /**
     * @var string[]
     */
    public array $certifications;

    /**
     * @var QuoteCostBreakdownRowParamBag[]
     */
    public array $costBreakdown;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'price_tax_exc'     => 'priceTaxExc',
            'delay_days'        => 'delayDays',
            'conditions'        => 'conditions',
            'engagement_months' => 'engagementMonths',
            'on_call_247'       => 'onCall247',
            'rse_score'         => 'rseScore',
            'references_count'  => 'referencesCount',
            'certifications'    => 'certifications',
            'cost_breakdown'    => 'costBreakdown',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'price_tax_exc'     => ['required', 'numeric', 'min:0'],
            'delay_days'        => ['nullable', 'integer', 'min:0'],
            'conditions'        => ['nullable', 'string', 'max:2000'],
            'engagement_months' => ['nullable', 'integer', 'min:0'],
            'on_call_247'       => ['nullable', 'boolean'],
            'rse_score'         => ['nullable', 'integer', 'between:0,100'],
            'references_count'  => ['nullable', 'integer', 'min:0'],
            'certifications'    => ['array'],
            'cost_breakdown'    => ['array'],
        ];
    }
}
