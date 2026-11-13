<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceAssignmentDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceAssignmentListDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceAvailabilityReviewDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceResourceAssignmentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceResourceAssignmentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServiceResourceAssignmentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServiceResourceAssignmentDataApiRepository;
use Tests\TestCase;

class OrigamiServiceResourceAssignmentDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/service_resources/resource-assignments` should send the place and the day, and read the resource of
     * each assignment.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServiceResourceAssignmentApiData(['id' => 1])])));
        $paramBag = new ListServiceResourceAssignmentRequestParamBag;
        $paramBag->setFilters(['service_site_id' => 42, 'date' => '2024-05-18']);

        $assignments = (new OrigamiServiceResourceAssignmentDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/resource-assignments');
        $this->assertLastApiRequestQueryWas(['filter' => ['service_site_id' => '42', 'date' => '2024-05-18'], 'page' => '1', 'item_per_page' => '20']);
        $this->assertInstanceOf(ServiceResourceAssignmentListDto::class, $assignments);
        $this->assertSame([1], $assignments->data->pluck('id')->all());
        $this->assertSame('Chair 3', $assignments->data->first()->resourceName);
    }

    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceResourceAssignmentApiData(['id' => 42])], 201));
        $paramBag = new CreateServiceResourceAssignmentRequestParamBag;
        $paramBag->serviceSiteId = 7;
        $paramBag->serviceResourceId = 3;
        $paramBag->sellerId = 5;
        $paramBag->dateStart = '2024-05-18';
        $paramBag->dateEnd = '2024-05-18';

        $assignment = (new OrigamiServiceResourceAssignmentDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/resource-assignments');
        $this->assertLastApiRequestJsonWas(['service_site_id' => 7, 'service_resource_id' => 3, 'seller_id' => 5, 'date_start' => '2024-05-18', 'date_end' => '2024-05-18']);
        $this->assertInstanceOf(ServiceResourceAssignmentDto::class, $assignment);
        $this->assertSame(ServiceAvailabilityReviewDtoStatusEnum::APPROVED, $assignment->status);
    }

    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceResourceAssignmentApiData(['id' => 42, 'service_resource_id' => 4])]));
        $paramBag = new UpdateServiceResourceAssignmentRequestParamBag;
        $paramBag->serviceResourceId = 4;

        $assignment = (new OrigamiServiceResourceAssignmentDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'modules/service_resources/resource-assignments/42');
        $this->assertLastApiRequestJsonWas(['service_resource_id' => 4]);
        $this->assertSame(4, $assignment->serviceResourceId);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiServiceResourceAssignmentDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'modules/service_resources/resource-assignments/42');
    }
}
