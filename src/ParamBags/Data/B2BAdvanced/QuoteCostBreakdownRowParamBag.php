<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

/**
 * One cost driver of the offer, named by its label. The API recomputes the amount of a rate driver from its
 * quantity and unit price.
 */
class QuoteCostBreakdownRowParamBag extends ParamBag
{
    public string $poste;

    public float $montant;

    public float $quantity;

    public float $unitPrice;

    protected function getApiKeysToProperties(): array
    {
        return [
            'poste'      => 'poste',
            'montant'    => 'montant',
            'quantity'   => 'quantity',
            'unit_price' => 'unitPrice',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'poste'      => ['required', 'string', 'max:100'],
            'montant'    => ['required', 'numeric', 'min:0'],
            'quantity'   => ['numeric', 'min:0'],
            'unit_price' => ['numeric', 'min:0'],
        ];
    }
}
