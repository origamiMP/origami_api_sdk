<?php

namespace Tests\Unit\Repositories\Api\Data\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductOfferDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServicePlanningListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceSlotDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServicePlanningDtoBookingUnitEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServicePlanningRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Service\OrigamiServicePlanningDataApiRepository;
use Tests\TestCase;

class OrigamiServicePlanningDataApiRepositoryTest extends TestCase
{
    /**
     * `GET catalog/service-plannings` should send the filter of an offer and the pagination.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServicePlanningApiData(['id' => 1, 'seller_id' => 8]),
            $this->mockServicePlanningApiData(['id' => 2, 'booking_unit' => ServicePlanningDtoBookingUnitEnum::DAY_RANGE->value, 'slot_duration_minutes' => null]),
        ])));
        $paramBag = new ListServicePlanningRequestParamBag;
        $paramBag->setFilters(['product_offer_id' => 11]);
        $paramBag->setWithoutPagination(true);

        $plannings = (new OrigamiServicePlanningDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'catalog/service-plannings');
        $this->assertLastApiRequestQueryWas([
            'filter'             => ['product_offer_id' => '11'],
            'page'               => '1',
            'item_per_page'      => '20',
            'without_pagination' => '1',
        ]);
        $this->assertInstanceOf(ServicePlanningListDto::class, $plannings);
        $this->assertSame([1, 2], $plannings->data->pluck('id')->all());
        $this->assertSame(8, $plannings->data->first()->sellerId);
        $this->assertSame(ServicePlanningDtoBookingUnitEnum::DAY_RANGE, $plannings->data->last()->bookingUnit);
        $this->assertNull($plannings->data->last()->slotDurationMinutes);
    }

    /**
     * A filter not available on `GET catalog/service-plannings` should not be sent.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([])));
        $paramBag = new ListServicePlanningRequestParamBag;
        $paramBag->setFilters(['product_id' => 12, 'seller_id' => 8]);

        (new OrigamiServicePlanningDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'        => ['product_id' => '12'],
            'page'          => '1',
            'item_per_page' => '20',
        ]);
    }

    /**
     * `GET catalog/service-plannings` should only request the available includes, and read them.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServicePlanningApiData([
                'id'      => 42,
                'offer'   => ['data' => ['id' => 11, 'product_id' => 12, 'seller_id' => 8, 'price_tax_exc' => '62.500000', 'price_tax_inc' => '75.000000']],
                'product' => ['data' => ['id' => 12, 'reference' => 'HAIRCUT', 'translations' => ['data' => [['locale' => 'en', 'language_id' => 1, 'default' => true, 'name' => 'Haircut']]]]],
                'seller'  => ['data' => $this->mockSellerApiData(['id' => 8])],
                'slots'   => ['data' => [$this->mockServiceSlotApiData(['id' => 7])]],
            ]),
        ])));
        $paramBag = new ListServicePlanningRequestParamBag;
        $paramBag->setIncludes(['offer', 'product', 'seller', 'slots', 'rules', 'unknown']);

        $plannings = (new OrigamiServicePlanningDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['offer', 'product', 'seller', 'slots'],
        ]);
        $planning = $plannings->data->first();
        $this->assertInstanceOf(ProductOfferDto::class, $planning->offer);
        $this->assertSame(11, $planning->offer->id);
        $this->assertInstanceOf(ProductDto::class, $planning->product);
        $this->assertSame('Haircut', $planning->product->translations->first()->name);
        $this->assertInstanceOf(SellerDto::class, $planning->seller);
        $this->assertSame(8, $planning->seller->id);
        $this->assertContainsOnlyInstancesOf(ServiceSlotDto::class, $planning->slots);
        $this->assertSame([7], $planning->slots->pluck('id')->all());
    }

    /**
     * A planning whose offer is gone has no product nor seller: the includes come as an empty `data`.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServicePlanningApiData(['id' => 42, 'product_id' => null, 'seller_id' => null, 'product' => ['data' => []], 'seller' => ['data' => []]]),
        ])));
        $paramBag = new ListServicePlanningRequestParamBag;
        $paramBag->setIncludes(['product', 'seller']);

        $planning = (new OrigamiServicePlanningDataApiRepository($restClient))->list($paramBag)->data->first();

        $this->assertNull($planning->sellerId);
        $this->assertNull($planning->product);
        $this->assertNull($planning->seller);
    }

    public function test_list_case_5(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServicePlanningApiData(['id' => 42])])));

        $plannings = (new OrigamiServicePlanningDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'catalog/service-plannings');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $plannings->data->first()->id);
    }
}
