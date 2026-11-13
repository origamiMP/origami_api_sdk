<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceResourceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceResourceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServiceResourceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServiceResourceDataApiRepository;
use Tests\TestCase;

class OrigamiServiceResourceDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/service_resources/resources` should send the place filter.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServiceResourceApiData(['id' => 1])])));
        $paramBag = new ListServiceResourceRequestParamBag;
        $paramBag->setFilters(['service_site_id' => 42]);

        $resources = (new OrigamiServiceResourceDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/resources');
        $this->assertLastApiRequestQueryWas(['filter' => ['service_site_id' => '42'], 'page' => '1', 'item_per_page' => '20']);
        $this->assertInstanceOf(ServiceResourceListDto::class, $resources);
        $this->assertSame([1], $resources->data->pluck('id')->all());
    }

    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceResourceApiData(['id' => 42])], 201));
        $paramBag = new CreateServiceResourceRequestParamBag;
        $paramBag->serviceSiteId = 7;
        $paramBag->type = 'chair';
        $paramBag->name = 'Chair 2';

        $resource = (new OrigamiServiceResourceDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/resources');
        $this->assertLastApiRequestJsonWas(['service_site_id' => 7, 'type' => 'chair', 'name' => 'Chair 2']);
        $this->assertInstanceOf(ServiceResourceDto::class, $resource);
        $this->assertSame(42, $resource->id);
    }

    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceResourceApiData(['id' => 42, 'is_active' => false])]));
        $paramBag = new UpdateServiceResourceRequestParamBag;
        $paramBag->isActive = false;

        $resource = (new OrigamiServiceResourceDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'modules/service_resources/resources/42');
        $this->assertLastApiRequestJsonWas(['is_active' => false]);
        $this->assertFalse($resource->isActive);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiServiceResourceDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'modules/service_resources/resources/42');
    }
}
