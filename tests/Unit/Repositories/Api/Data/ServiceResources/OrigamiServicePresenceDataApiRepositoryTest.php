<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceCreateSeriesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceCreateSeriesSkippedDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceListDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServicePresenceDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServicePresenceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServicePresenceSeriesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServicePresenceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServicePresenceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServicePresenceDataApiRepository;
use Tests\TestCase;

class OrigamiServicePresenceDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/service_resources/presences` should send the place and the day, and read the resource of
     * each presence.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServicePresenceApiData(['id' => 1])])));
        $paramBag = new ListServicePresenceRequestParamBag;
        $paramBag->setFilters(['service_site_id' => 42, 'date' => '2024-05-18']);

        $presences = (new OrigamiServicePresenceDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/presences');
        $this->assertLastApiRequestQueryWas(['filter' => ['service_site_id' => '42', 'date' => '2024-05-18'], 'page' => '1', 'item_per_page' => '20']);
        $this->assertInstanceOf(ServicePresenceListDto::class, $presences);
        $this->assertSame([1], $presences->data->pluck('id')->all());
        $this->assertSame('Chair 3', $presences->data->first()->resourceName);
    }

    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServicePresenceApiData(['id' => 42])], 201));
        $paramBag = new CreateServicePresenceRequestParamBag;
        $paramBag->serviceSiteId = 7;
        $paramBag->serviceResourceId = 3;
        $paramBag->sellerId = 5;
        $paramBag->date = '2024-05-18';
        $paramBag->startTime = '09:00';
        $paramBag->endTime = '18:00';

        $presence = (new OrigamiServicePresenceDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/presences');
        $this->assertLastApiRequestJsonWas(['service_site_id' => 7, 'service_resource_id' => 3, 'seller_id' => 5, 'date' => '2024-05-18', 'start_time' => '09:00', 'end_time' => '18:00']);
        $this->assertInstanceOf(ServicePresenceDto::class, $presence);
        $this->assertSame(ServicePresenceDtoStatusEnum::APPROVED, $presence->status);
    }

    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServicePresenceApiData(['id' => 42, 'service_resource_id' => 4])]));
        $paramBag = new UpdateServicePresenceRequestParamBag;
        $paramBag->serviceResourceId = 4;

        $presence = (new OrigamiServicePresenceDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'modules/service_resources/presences/42');
        $this->assertLastApiRequestJsonWas(['service_resource_id' => 4]);
        $this->assertSame(4, $presence->serviceResourceId);
    }

    /**
     * `PATCH modules/service_resources/presences/{id}` should send the new hours of the day, without the resource when it
     * does not change.
     */
    public function test_update_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServicePresenceApiData([
            'id' => 42, 'starts_at' => '2024-05-18 10:00:00', 'ends_at' => '2024-05-18 16:30:00',
        ])]));
        $paramBag = new UpdateServicePresenceRequestParamBag;
        $paramBag->date = '2024-05-18';
        $paramBag->startTime = '10:00';
        $paramBag->endTime = '16:30';

        $presence = (new OrigamiServicePresenceDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestJsonWas(['date' => '2024-05-18', 'start_time' => '10:00', 'end_time' => '16:30']);
        $this->assertSame('2024-05-18 16:30:00', $presence->endsAt);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiServicePresenceDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'modules/service_resources/presences/42');
    }

    /**
     * `POST modules/service_resources/presences/series` should send the weekday and the dates of the series, and read the
     * presences created, with their series, and the dates skipped.
     */
    public function test_create_series_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse([
            'data' => [
                $this->mockServicePresenceApiData(['id' => 1, 'series_id' => '9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b']),
                $this->mockServicePresenceApiData(['id' => 2, 'series_id' => '9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b']),
            ],
            'meta' => ['skipped' => [['date' => '2024-05-25', 'code' => '130108']]],
        ], 201));
        $paramBag = new CreateServicePresenceSeriesRequestParamBag;
        $paramBag->serviceSiteId = 4;
        $paramBag->serviceResourceId = 3;
        $paramBag->sellerId = 8;
        $paramBag->weekday = 6;
        $paramBag->startTime = '09:00';
        $paramBag->endTime = '17:00';
        $paramBag->from = '2024-05-18';
        $paramBag->until = '2024-06-01';

        $series = (new OrigamiServicePresenceDataApiRepository($restClient))->createSeries($paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/presences/series');
        $this->assertLastApiRequestJsonWas([
            'service_site_id'     => 4,
            'service_resource_id' => 3,
            'seller_id'           => 8,
            'weekday'             => 6,
            'start_time'          => '09:00',
            'end_time'            => '17:00',
            'from'                => '2024-05-18',
            'until'               => '2024-06-01',
        ]);
        $this->assertInstanceOf(ServicePresenceCreateSeriesDto::class, $series);
        $this->assertContainsOnlyInstancesOf(ServicePresenceDto::class, $series->data);
        $this->assertSame([1, 2], $series->data->pluck('id')->all());
        $this->assertSame('9b2f3a4e-1c2d-4e5f-8a9b-0c1d2e3f4a5b', $series->data->first()->seriesId);
        $this->assertContainsOnlyInstancesOf(ServicePresenceCreateSeriesSkippedDto::class, $series->skipped);
        $this->assertSame([['date' => '2024-05-25', 'code' => '130108']], $series->skipped->map->toArray()->all());
    }
}
