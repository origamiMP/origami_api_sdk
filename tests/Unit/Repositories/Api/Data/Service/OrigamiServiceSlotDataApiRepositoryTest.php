<?php

namespace Tests\Unit\Repositories\Api\Data\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceSlotListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServiceSlotRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Service\OrigamiServiceSlotDataApiRepository;
use Tests\TestCase;

class OrigamiServiceSlotDataApiRepositoryTest extends TestCase
{
    /**
     * `GET catalog/service-slots` should send the bounds of the starts and the pagination, and not a filter it does not
     * offer.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServiceSlotApiData(['id' => 1]),
            $this->mockServiceSlotApiData(['id' => 2]),
        ])));
        $paramBag = new ListServiceSlotRequestParamBag;
        $paramBag->setFilters(['starts_after' => '2024-05-12 00:00:00', 'starts_before' => '2024-05-12 23:59:59', 'seller_id' => 8]);
        $paramBag->setWithoutPagination(true);

        $slots = (new OrigamiServiceSlotDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'catalog/service-slots');
        $this->assertLastApiRequestQueryWas([
            'filter'             => ['starts_after' => '2024-05-12 00:00:00', 'starts_before' => '2024-05-12 23:59:59'],
            'page'               => '1',
            'item_per_page'      => '20',
            'without_pagination' => '1',
        ]);
        $this->assertInstanceOf(ServiceSlotListDto::class, $slots);
        $this->assertSame([1, 2], $slots->data->pluck('id')->all());
    }

    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServiceSlotApiData(['id' => 42])])));

        $slots = (new OrigamiServiceSlotDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'catalog/service-slots');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $slots->data->first()->id);
    }
}
