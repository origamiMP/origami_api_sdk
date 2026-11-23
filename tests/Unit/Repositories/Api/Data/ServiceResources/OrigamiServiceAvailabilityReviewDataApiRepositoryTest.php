<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesFailedDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewPresenceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewRejectSeriesDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceAvailabilityReviewDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceAvailabilityReviewRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\RejectSeriesServiceAvailabilityReviewRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\RejectServiceAvailabilityReviewRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServiceAvailabilityReviewDataApiRepository;
use Tests\TestCase;

class OrigamiServiceAvailabilityReviewDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/service_resources/availability-reviews` should send the status filter, and read the slot or the
     * presence each review is about, with the series of a regular request.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServiceAvailabilityReviewApiData(['id' => 1, 'slot' => ['id' => 3, 'starts_at' => '2024-05-12 10:00:00', 'ends_at' => '2024-05-12 11:00:00']]),
            $this->mockServiceAvailabilityReviewApiData([
                'id'                  => 4,
                'service_planning_id' => null,
                'slot'                => null,
                'presence'            => $this->mockServiceAvailabilityReviewPresenceApiData(['id' => 9, 'series_id' => '9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b']),
            ]),
        ])));
        $paramBag = new ListServiceAvailabilityReviewRequestParamBag;
        $paramBag->setFilters(['status' => 'pending']);

        $reviews = (new OrigamiServiceAvailabilityReviewDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/availability-reviews');
        $this->assertLastApiRequestQueryWas(['filter' => ['status' => 'pending'], 'page' => '1', 'item_per_page' => '20']);
        $this->assertInstanceOf(ServiceAvailabilityReviewListDto::class, $reviews);
        $this->assertSame(3, $reviews->data->first()->slot->id);
        $this->assertNull($reviews->data->first()->presence);
        $presence = $reviews->data->last()->presence;
        $this->assertInstanceOf(ServiceAvailabilityReviewPresenceDto::class, $presence);
        $this->assertSame(9, $presence->id);
        $this->assertSame('9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b', $presence->seriesId);
        $this->assertSame([3, 'chair', 'Chair 3'], [$presence->resourceId, $presence->resourceType, $presence->resourceName]);
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

    /**
     * `POST modules/service_resources/availability-reviews/series/{series}/approve` should read the reviews granted and
     * those left pending.
     */
    public function test_approve_series_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse([
            'data' => [$this->mockServiceAvailabilityReviewApiData(['id' => 1, 'status' => ServiceAvailabilityReviewDtoStatusEnum::APPROVED->value])],
            'meta' => ['failed' => [['service_availability_review_id' => 2, 'service_presence_id' => 12, 'code' => '130108']]],
        ]));

        $approval = (new OrigamiServiceAvailabilityReviewDataApiRepository($restClient))->approveSeries('9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b');

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/availability-reviews/series/9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b/approve');
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(ServiceAvailabilityReviewApproveSeriesDto::class, $approval);
        $this->assertSame([1], $approval->data->pluck('id')->all());
        $this->assertContainsOnlyInstancesOf(ServiceAvailabilityReviewApproveSeriesFailedDto::class, $approval->failed);
        $this->assertSame([2, 12, '130108'], [$approval->failed->first()->serviceAvailabilityReviewId, $approval->failed->first()->servicePresenceId, $approval->failed->first()->code]);
    }

    /**
     * When no presence of the series can be granted any more, nothing should be approved and every review should come
     * back as failed.
     */
    public function test_approve_series_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [], 'meta' => ['failed' => [
            ['service_availability_review_id' => 1, 'service_presence_id' => 11, 'code' => '130109'],
            ['service_availability_review_id' => 2, 'service_presence_id' => 12, 'code' => '130108'],
        ]]]));

        $approval = (new OrigamiServiceAvailabilityReviewDataApiRepository($restClient))->approveSeries('9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b');

        $this->assertCount(0, $approval->data);
        $this->assertSame([1, 2], $approval->failed->pluck('serviceAvailabilityReviewId')->all());
    }

    /**
     * `POST modules/service_resources/availability-reviews/series/{series}/reject` should send the reason told to the
     * provider, and read the reviews rejected.
     */
    public function test_reject_series_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockServiceAvailabilityReviewApiData(['id' => 1, 'status' => ServiceAvailabilityReviewDtoStatusEnum::REJECTED->value]),
            $this->mockServiceAvailabilityReviewApiData(['id' => 2, 'status' => ServiceAvailabilityReviewDtoStatusEnum::REJECTED->value]),
        ]]));
        $paramBag = new RejectSeriesServiceAvailabilityReviewRequestParamBag;
        $paramBag->note = 'Place closed on Saturdays';

        $rejection = (new OrigamiServiceAvailabilityReviewDataApiRepository($restClient))->rejectSeries('9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b', $paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/availability-reviews/series/9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b/reject');
        $this->assertLastApiRequestJsonWas(['note' => 'Place closed on Saturdays']);
        $this->assertInstanceOf(ServiceAvailabilityReviewRejectSeriesDto::class, $rejection);
        $this->assertSame([1, 2], $rejection->data->pluck('id')->all());
    }
}
