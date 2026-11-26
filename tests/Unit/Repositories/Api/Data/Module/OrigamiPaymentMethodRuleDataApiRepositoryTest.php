<?php

namespace Tests\Unit\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\PaymentMethodRuleListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\SyncPaymentMethodRule\SyncPaymentMethodRuleParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\SyncPaymentMethodRuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module\OrigamiPaymentMethodRuleDataApiRepository;
use Tests\TestCase;

class OrigamiPaymentMethodRuleDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/{id}/payment_method_rules` answers a non-paginated list: each of its items should be read as a
     * rule, its amounts (sent as strings) as floats, or null when not set.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockPaymentMethodRuleApiData(['id' => 1, 'min_amount' => '100.00', 'max_amount' => null, 'customer_group_ids' => []]),
            $this->mockPaymentMethodRuleApiData(['id' => 2, 'min_amount' => null, 'max_amount' => '500.50', 'customer_group_ids' => [42, 43]]),
        ]]));

        $paymentMethodRules = (new OrigamiPaymentMethodRuleDataApiRepository($restClient))->list(42);

        $this->assertLastApiRequestWas('GET', 'modules/42/payment_method_rules');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertInstanceOf(PaymentMethodRuleListDto::class, $paymentMethodRules);
        $this->assertSame([1, 2], $paymentMethodRules->data->pluck('id')->all());
        $this->assertSame([100.0, null], $paymentMethodRules->data->pluck('minAmount')->all());
        $this->assertSame([null, 500.5], $paymentMethodRules->data->pluck('maxAmount')->all());
        $this->assertSame([[], [42, 43]], $paymentMethodRules->data->pluck('customerGroupIds')->all());
    }

    /**
     * `GET modules/{id}/payment_method_rules` for a module without rules should give an empty list.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => []]));

        $paymentMethodRules = (new OrigamiPaymentMethodRuleDataApiRepository($restClient))->list(42);

        $this->assertLastApiRequestWas('GET', 'modules/42/payment_method_rules');
        $this->assertInstanceOf(PaymentMethodRuleListDto::class, $paymentMethodRules);
        $this->assertTrue($paymentMethodRules->data->isEmpty());
    }

    /**
     * `PUT modules/{id}/payment_method_rules` should send every rule as JSON, and read the rules saved.
     */
    public function test_sync_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockPaymentMethodRuleApiData(['id' => 1, 'payment_method' => 'PAYIN']),
            $this->mockPaymentMethodRuleApiData(['id' => 2, 'payment_method' => 'DEBIT_AGREEMENT']),
        ]]));
        $cardRule = new SyncPaymentMethodRuleParamBag;
        $cardRule->paymentMethod = 'PAYIN';
        $cardRule->minAmount = 100.0;
        $cardRule->maxAmount = null;
        $cardRule->customerGroupIds = [42, 43];
        $debitRule = new SyncPaymentMethodRuleParamBag;
        $debitRule->paymentMethod = 'DEBIT_AGREEMENT';
        $debitRule->minAmount = null;
        $debitRule->maxAmount = 500.5;
        $debitRule->customerGroupIds = [];
        $paramBag = new SyncPaymentMethodRuleRequestParamBag;
        $paramBag->rules = [$cardRule, $debitRule];

        $paymentMethodRules = (new OrigamiPaymentMethodRuleDataApiRepository($restClient))->sync(42, $paramBag);

        $this->assertLastApiRequestWas('PUT', 'modules/42/payment_method_rules');
        $this->assertLastApiRequestJsonWas(['rules' => [
            ['payment_method' => 'PAYIN', 'min_amount' => 100.0, 'max_amount' => null, 'customer_group_ids' => [42, 43]],
            ['payment_method' => 'DEBIT_AGREEMENT', 'min_amount' => null, 'max_amount' => 500.5, 'customer_group_ids' => []],
        ]]);
        $this->assertInstanceOf(PaymentMethodRuleListDto::class, $paymentMethodRules);
        $this->assertSame([1, 2], $paymentMethodRules->data->pluck('id')->all());
    }

    /**
     * `PUT modules/{id}/payment_method_rules` with an empty list should send it as such, so that the API removes every
     * rule of the module.
     */
    public function test_sync_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => []]));
        $paramBag = new SyncPaymentMethodRuleRequestParamBag;
        $paramBag->rules = [];

        $paymentMethodRules = (new OrigamiPaymentMethodRuleDataApiRepository($restClient))->sync(42, $paramBag);

        $this->assertLastApiRequestWas('PUT', 'modules/42/payment_method_rules');
        $this->assertLastApiRequestJsonWas(['rules' => []]);
        $this->assertTrue($paymentMethodRules->data->isEmpty());
    }

    /**
     * `PUT modules/{id}/payment_method_rules` should not send the properties of a rule left unset (here, a rule on the
     * customer groups only).
     */
    public function test_sync_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [$this->mockPaymentMethodRuleApiData()]]));
        $rule = new SyncPaymentMethodRuleParamBag;
        $rule->paymentMethod = 'PAYIN';
        $rule->customerGroupIds = [42];
        $paramBag = new SyncPaymentMethodRuleRequestParamBag;
        $paramBag->rules = [$rule];

        (new OrigamiPaymentMethodRuleDataApiRepository($restClient))->sync(42, $paramBag);

        $this->assertLastApiRequestJsonWas(['rules' => [['payment_method' => 'PAYIN', 'customer_group_ids' => [42]]]]);
    }

    /**
     * `PUT modules/{id}/payment_method_rules` should round the amounts to 2 decimals, the only precision the API
     * accepts: a computed amount (`0.1 + 0.2` gives `0.30000000000000004`) would otherwise be refused.
     */
    public function test_sync_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [$this->mockPaymentMethodRuleApiData()]]));
        $rule = new SyncPaymentMethodRuleParamBag;
        $rule->paymentMethod = 'PAYIN';
        $rule->minAmount = 0.1 + 0.2;
        $rule->maxAmount = 99.999;
        $paramBag = new SyncPaymentMethodRuleRequestParamBag;
        $paramBag->rules = [$rule];

        (new OrigamiPaymentMethodRuleDataApiRepository($restClient))->sync(42, $paramBag);

        $this->assertLastApiRequestJsonWas(['rules' => [['payment_method' => 'PAYIN', 'min_amount' => 0.3, 'max_amount' => 100.0]]]);
    }
}
