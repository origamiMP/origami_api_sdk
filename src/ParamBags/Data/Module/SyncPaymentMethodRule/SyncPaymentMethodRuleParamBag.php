<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\SyncPaymentMethodRule;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

/**
 * One eligibility rule of a payment method: the customer groups and / or the cart amount range allowed to use it.
 */
class SyncPaymentMethodRuleParamBag extends ParamBag
{
    public string $paymentMethod;

    public ?string $minAmount;

    public ?string $maxAmount;

    /** @var array<int, int> */
    public array $customerGroupIds;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'paymentMethod'    => 'payment_method',
            'minAmount'        => fn (?string $minAmount) => ['min_amount' => $this->roundAmount($minAmount)],
            'maxAmount'        => fn (?string $maxAmount) => ['max_amount' => $this->roundAmount($maxAmount)],
            'customerGroupIds' => 'customer_group_ids',
        ];
    }

    /**
     * The API only accepts amounts with at most 2 decimals: an amount with more (`'99.999'`) is rounded half up
     * (`'100.00'`). A value that is not a number is sent as is, for the API to refuse it.
     */
    private function roundAmount(?string $amount): ?string
    {
        if (is_null($amount)) {
            return null;
        }

        try {
            return bcround($amount, 2);
        } catch (\ValueError) {
            return $amount;
        }
    }
}
