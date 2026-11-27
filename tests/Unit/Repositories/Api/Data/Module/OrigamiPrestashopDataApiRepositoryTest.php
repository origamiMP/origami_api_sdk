<?php

namespace Tests\Unit\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop\PrestashopOrderStateListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop\PrestashopShippingZoneListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop\PrestashopTaxListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetPrestashopOrderStatesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetPrestashopShippingZonesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetPrestashopTaxesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module\OrigamiPrestashopDataApiRepository;
use Tests\TestCase;

class OrigamiPrestashopDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/prestashop/order_states` should send the shop id, and read the order states of the shop, ids as
     * integers.
     */
    public function test_get_order_states_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockPrestashopOrderStateApiData(['prestashop_id' => '1', 'name' => 'Awaiting check payment']),
            $this->mockPrestashopOrderStateApiData(['prestashop_id' => '2', 'color' => null]),
        ]]));
        $paramBag = new GetPrestashopOrderStatesRequestParamBag;
        $paramBag->shopId = 42;

        $orderStates = (new OrigamiPrestashopDataApiRepository($restClient))->getOrderStates($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/prestashop/order_states');
        $this->assertLastApiRequestQueryWas(['shop-id' => '42']);
        $this->assertInstanceOf(PrestashopOrderStateListDto::class, $orderStates);
        $this->assertSame([1, 2], $orderStates->data->pluck('prestashopId')->all());
        $this->assertSame('Awaiting check payment', $orderStates->data->first()->name);
        $this->assertSame(['#4169E1', null], $orderStates->data->pluck('color')->all());
    }

    public function test_get_order_states_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => []]));
        $paramBag = new GetPrestashopOrderStatesRequestParamBag;
        $paramBag->shopId = 42;

        $orderStates = (new OrigamiPrestashopDataApiRepository($restClient))->getOrderStates($paramBag);

        $this->assertTrue($orderStates->data->isEmpty());
    }

    /**
     * `GET modules/prestashop/shipping_zones` should send the shop id, and read the shipping zones of the shop, active
     * or not.
     */
    public function test_get_shipping_zones_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockPrestashopShippingZoneApiData(['prestashop_id' => '1', 'name' => 'Europe']),
            $this->mockPrestashopShippingZoneApiData(['prestashop_id' => '2', 'name' => 'Oceania', 'active' => '0']),
        ]]));
        $paramBag = new GetPrestashopShippingZonesRequestParamBag;
        $paramBag->shopId = 42;

        $shippingZones = (new OrigamiPrestashopDataApiRepository($restClient))->getShippingZones($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/prestashop/shipping_zones');
        $this->assertLastApiRequestQueryWas(['shop-id' => '42']);
        $this->assertInstanceOf(PrestashopShippingZoneListDto::class, $shippingZones);
        $this->assertSame([1, 2], $shippingZones->data->pluck('prestashopId')->all());
        $this->assertSame(['Europe', 'Oceania'], $shippingZones->data->pluck('name')->all());
        $this->assertSame([true, false], $shippingZones->data->pluck('active')->all());
    }

    /**
     * `GET modules/prestashop/taxes` should send the shop id, and read the taxes of the shop.
     */
    public function test_get_taxes_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockPrestashopTaxApiData(['prestashop_id' => '1', 'name' => 'FR Taux standard (20%)']),
            $this->mockPrestashopTaxApiData(['prestashop_id' => '2', 'name' => 'FR Taux réduit (5.5%)']),
        ]]));
        $paramBag = new GetPrestashopTaxesRequestParamBag;
        $paramBag->shopId = 42;

        $taxes = (new OrigamiPrestashopDataApiRepository($restClient))->getTaxes($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/prestashop/taxes');
        $this->assertLastApiRequestQueryWas(['shop-id' => '42']);
        $this->assertInstanceOf(PrestashopTaxListDto::class, $taxes);
        $this->assertSame([1, 2], $taxes->data->pluck('prestashopId')->all());
        $this->assertSame(['FR Taux standard (20%)', 'FR Taux réduit (5.5%)'], $taxes->data->pluck('name')->all());
    }
}
