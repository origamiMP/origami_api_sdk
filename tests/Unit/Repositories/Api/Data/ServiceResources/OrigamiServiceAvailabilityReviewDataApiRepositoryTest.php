<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewListDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceAvailabilityReviewDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceAvailabilityReviewRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\RejectServiceAvailabilityReviewRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServiceAvailabilityReviewDataApiRepository;
use Tests\TestCase;

class OrigamiServiceAvailabilityReviewDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/service_resources/availability-reviews` should send the status filter, and read the rule, the
     * opening, the slot or the resource asked for each review is about.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServiceAvailabilityReviewApiData(['id' => 1]),
            $this->mockServiceAvailabilityReviewApiData(['id' => 2, 'rule' => null, 'slot' => ['id' => 3, 'starts_at' => '2024-05-12 10:00:00', 'ends_at' => '2024-05-12 11:00:00']]),
            $this->mockServiceAvailabilityReviewApiData(['id' => 4, 'service_planning_id' => null, 'rule' => null, 'presence' => $this->mockServicePresenceApiData(['status' => 'pending'])]),
        ])));
        $paramBag = new ListServiceAvailabilityReviewRequestParamBag;
        $paramBag->setFilters(['status' => 'pending']);

        $reviews = (new OrigamiServiceAvailabilityReviewDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/availability-reviews');
        $this->assertLastApiRequestQueryWas(['filter' => ['status' => 'pending'], 'page' => '1', 'item_per_page' => '20']);
        $this->assertInstanceOf(ServiceAvailabilityReviewListDto::class, $reviews);
        $this->assertSame('10:00:00', $reviews->data->first()->rule->startTime);
        $this->assertSame(3, $reviews->data->get(1)->slot->id);
        $this->assertNull($reviews->data->last()->servicePlanningId);
        $this->assertSame('Chair 3', $reviews->data->last()->presence->resourceName);
    }

    public function test_approve_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceAvailabilityReviewApiData([
            'id' => 42, 'status' => ServiceAvailabilityReviewDtoStatusEnum::APPROVED->value, 'reviewed_at' => '2024-05-12 17:00:00',
        ])]));

        $review = (new OrigamiServiceAvailabilityReviewDataApiRepository($restClient))->approve(42);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/availability-reviews/42/approve');
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(ServiceAvailabilityReviewDto::class, $review);
        $this->assertSame(ServiceAvailabilityReviewDtoStatusEnum::APPROVED, $review->status);
    }

    /**
     * `POST modules/service_resources/availability-reviews/{id}/reject` should send the reason told to the provider.
     */
    public function test_reject_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceAvailabilityReviewApiData([
            'id' => 42, 'status' => ServiceAvailabilityReviewDtoStatusEnum::REJECTED->value, 'note' => 'Place closed on Saturdays',
        ])]));
        $paramBag = new RejectServiceAvailabilityReviewRequestParamBag;
        $paramBag->note = 'Place closed on Saturdays';

        $review = (new OrigamiServiceAvailabilityReviewDataApiRepository($restClient))->reject(42, $paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/availability-reviews/42/reject');
        $this->assertLastApiRequestJsonWas(['note' => 'Place closed on Saturdays']);
        $this->assertSame(ServiceAvailabilityReviewDtoStatusEnum::REJECTED, $review->status);
    }
}
