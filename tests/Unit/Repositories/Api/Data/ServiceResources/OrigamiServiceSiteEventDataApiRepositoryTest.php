<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventCreateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceSiteEventRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceSiteEventRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServiceSiteEventDataApiRepository;
use Tests\TestCase;

class OrigamiServiceSiteEventDataApiRepositoryTest extends TestCase
{
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServiceSiteEventApiData(['id' => 1])])));
        $paramBag = new ListServiceSiteEventRequestParamBag;
        $paramBag->setFilters(['service_site_id' => 42]);

        $events = (new OrigamiServiceSiteEventDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/events');
        $this->assertLastApiRequestQueryWas(['filter' => ['service_site_id' => '42'], 'page' => '1', 'item_per_page' => '20']);
        $this->assertInstanceOf(ServiceSiteEventListDto::class, $events);
        $this->assertSame([1], $events->data->pluck('id')->all());
    }

    /**
     * `POST modules/service_resources/events` should send its dates in the format the API expects.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse([
            'data' => $this->mockServiceSiteEventApiData(['id' => 42]),
            'meta' => ['booked_slot_ids_to_handle' => [21]],
        ], 201));
        $paramBag = new CreateServiceSiteEventRequestParamBag;
        $paramBag->serviceSiteId = 7;
        $paramBag->label = 'Works';
        $paramBag->startsAt = Carbon::parse('2024-05-12 09:00:00');
        $paramBag->endsAt = Carbon::parse('2024-05-12 13:00:00');
        $paramBag->resourceIds = [3];

        $creation = (new OrigamiServiceSiteEventDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/events');
        $this->assertLastApiRequestJsonWas([
            'service_site_id' => 7,
            'label'           => 'Works',
            'starts_at'       => '2024-05-12 09:00:00',
            'ends_at'         => '2024-05-12 13:00:00',
            'resource_ids'    => [3],
        ]);
        $this->assertInstanceOf(ServiceSiteEventCreateDto::class, $creation);
        $this->assertInstanceOf(ServiceSiteEventDto::class, $creation->data);
        $this->assertSame(42, $creation->data->id);
        $this->assertSame([21], $creation->bookedSlotIdsToHandle);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiServiceSiteEventDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'modules/service_resources/events/42');
    }
}
