<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\Address\AddressDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteListDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources\ServiceSiteBoardBookingDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\GetServiceSiteBoardRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ServiceSiteAddressParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ServiceSiteOpeningHourParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServiceSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServiceSiteDataApiRepository;
use Tests\TestCase;

class OrigamiServiceSiteDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/service_resources/sites` should send its filters and pagination, and read each place.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServiceSiteApiData(['id' => 1]),
            $this->mockServiceSiteApiData(['id' => 2]),
        ])));
        $paramBag = new ListServiceSiteRequestParamBag;
        $paramBag->setFilters(['is_active' => 1, 'unknown_filter' => 'value']);
        $paramBag->setWithoutPagination(true);

        $sites = (new OrigamiServiceSiteDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/sites');
        $this->assertLastApiRequestQueryWas(['filter' => ['is_active' => '1'], 'page' => '1', 'item_per_page' => '20', 'without_pagination' => '1']);
        $this->assertInstanceOf(ServiceSiteListDto::class, $sites);
        $this->assertSame([1, 2], $sites->data->pluck('id')->all());
    }

    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([])));

        $sites = (new OrigamiServiceSiteDataApiRepository($restClient))->list();

        $this->assertLastApiRequestQueryWas([]);
        $this->assertCount(0, $sites->data);
    }

    /**
     * `GET modules/service_resources/sites/{id}` should read the place with its address, opening hours, providers,
     * resources and attached plannings.
     */
    public function test_get_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceSiteApiData(['id' => 42, 'seller_ids' => [8, 9]])]));

        $site = (new OrigamiServiceSiteDataApiRepository($restClient))->get(42);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/sites/42');
        $this->assertInstanceOf(ServiceSiteDto::class, $site);
        $this->assertSame(42, $site->id);
        $this->assertInstanceOf(AddressDto::class, $site->address);
        $this->assertSame('Paris', $site->address->city);
        $this->assertSame('09:00', $site->openingHours->first()->opensAt);
        $this->assertSame([8, 9], $site->sellerIds);
        $this->assertSame('Chair 1', $site->resources->first()->name);
        $this->assertSame(10, $site->plannings->first()->bufferAfterMinutes);
    }

    /**
     * A place without address should have a null address.
     */
    public function test_get_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceSiteApiData(['address' => null])]));

        $site = (new OrigamiServiceSiteDataApiRepository($restClient))->get(42);

        $this->assertNull($site->address);
    }

    /**
     * `POST modules/service_resources/sites` should send the place with its address and opening hours.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceSiteApiData(['id' => 42])], 201));
        $address = new ServiceSiteAddressParamBag;
        $address->addressLine1 = '10 rue de Rivoli';
        $address->postcode = '75004';
        $address->city = 'Paris';
        $address->country = 'France';
        $monday = new ServiceSiteOpeningHourParamBag;
        $monday->weekday = 1;
        $monday->opensAt = '09:00';
        $monday->closesAt = '19:00';
        $paramBag = new CreateServiceSiteRequestParamBag;
        $paramBag->name = 'Marais';
        $paramBag->timezone = 'Europe/Paris';
        $paramBag->address = $address;
        $paramBag->openingHours = [$monday];

        $site = (new OrigamiServiceSiteDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/service_resources/sites');
        $this->assertLastApiRequestJsonWas([
            'name'          => 'Marais',
            'timezone'      => 'Europe/Paris',
            'address'       => ['address_line1' => '10 rue de Rivoli', 'postcode' => '75004', 'city' => 'Paris', 'country' => 'France'],
            'opening_hours' => [['weekday' => 1, 'opens_at' => '09:00', 'closes_at' => '19:00']],
        ]);
        $this->assertSame(42, $site->id);
    }

    /**
     * `PATCH modules/service_resources/sites/{id}` should send the providers of the place, and read it back.
     */
    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceSiteApiData(['id' => 42, 'seller_ids' => [8, 9]])]));
        $paramBag = new UpdateServiceSiteRequestParamBag;
        $paramBag->sellerIds = [8, 9];

        $site = (new OrigamiServiceSiteDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'modules/service_resources/sites/42');
        $this->assertLastApiRequestJsonWas(['seller_ids' => [8, 9]]);
        $this->assertSame([8, 9], $site->sellerIds);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiServiceSiteDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'modules/service_resources/sites/42');
    }

    /**
     * `GET modules/service_resources/sites/{id}/board` should send the period, and read the slots with their bookings.
     */
    public function test_get_board_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceSiteBoardApiData([
            'events' => [['id' => 5, 'label' => 'Works', 'starts_at' => '2024-05-12 09:00:00', 'ends_at' => '2024-05-12 13:00:00', 'all_resources' => false, 'resource_ids' => [3]]],
        ])]));
        $paramBag = new GetServiceSiteBoardRequestParamBag;
        $paramBag->from = '2024-05-12';
        $paramBag->to = '2024-05-12';

        $board = (new OrigamiServiceSiteDataApiRepository($restClient))->getBoard(42, $paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/sites/42/board');
        $this->assertLastApiRequestQueryWas(['from' => '2024-05-12', 'to' => '2024-05-12']);
        $this->assertInstanceOf(ServiceSiteBoardDto::class, $board);
        $slot = $board->slots->first();
        $this->assertEquals(Carbon::parse('2024-05-12 10:00:00'), $slot->startsAt);
        $this->assertSame(ServiceSiteBoardBookingDtoStatusEnum::CONFIRMED, $slot->bookings->first()->status);
        $this->assertSame([3], $board->events->first()->resourceIds);
    }
}
