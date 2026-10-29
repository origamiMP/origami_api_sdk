<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced\RespondQuoteResponse;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

/**
 * One cost driver of the offer, named by its label. The API recomputes the amount of a rate driver from its
 * quantity and unit price.
 */
class RespondQuoteResponseCostBreakdownRowParamBag extends ParamBag
{
    public string $poste;

    public float $montant;

    public float $quantity;

    public float $unitPrice;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'poste'     => 'poste',
            'montant'   => 'montant',
            'quantity'  => 'quantity',
            'unitPrice' => 'unit_price',
        ];
    }
}
