<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\SyncPaymentMethodRule;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

/**
 * One eligibility rule of a payment method: the customer groups and / or the cart amount range allowed to use it.
 */
class SyncPaymentMethodRuleParamBag extends ParamBag
{
    public string $paymentMethod;

    public ?float $minAmount;

    public ?float $maxAmount;

    /** @var array<int, int> */
    public array $customerGroupIds;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'paymentMethod'    => 'payment_method',
            'minAmount'        => fn (?float $minAmount) => ['min_amount' => $this->roundAmount($minAmount)],
            'maxAmount'        => fn (?float $maxAmount) => ['max_amount' => $this->roundAmount($maxAmount)],
            'customerGroupIds' => 'customer_group_ids',
        ];
    }

    /**
     * The API only accepts amounts with at most 2 decimals: a computed float (`0.1 + 0.2`) would be refused.
     */
    private function roundAmount(?float $amount): ?float
    {
        return is_null($amount) ? null : round($amount, 2);
    }
}
