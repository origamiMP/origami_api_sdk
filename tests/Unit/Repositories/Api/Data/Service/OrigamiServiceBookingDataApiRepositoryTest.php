<?php

namespace Tests\Unit\Repositories\Api\Data\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingCustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingOrderDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingSellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceSlotDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\UpdateServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Service\OrigamiServiceBookingDataApiRepository;
use Tests\TestCase;

class OrigamiServiceBookingDataApiRepositoryTest extends TestCase
{
    /**
     * `GET catalog/service-bookings` should send the filters of a day of a seller, the pagination and the search.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServiceBookingApiData(['id' => 1]),
            $this->mockServiceBookingApiData(['id' => 2, 'status' => ServiceBookingDtoStatusEnum::HONORED->value]),
        ])));
        $paramBag = new ListServiceBookingRequestParamBag;
        $paramBag->setFilters(['seller_id' => 8, 'starts_between' => '2024-05-12,2024-05-12', 'status' => 'confirmed,honored']);
        $paramBag->setWithoutPagination(true);
        $paramBag->setSearch('john');

        $bookings = (new OrigamiServiceBookingDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'catalog/service-bookings');
        $this->assertLastApiRequestQueryWas([
            'filter'             => ['seller_id' => '8', 'starts_between' => '2024-05-12,2024-05-12', 'status' => 'confirmed,honored'],
            'page'               => '1',
            'item_per_page'      => '20',
            'without_pagination' => '1',
            'search'             => 'john',
        ]);
        $this->assertInstanceOf(ServiceBookingListDto::class, $bookings);
        $this->assertSame([1, 2], $bookings->data->pluck('id')->all());
        $this->assertSame(ServiceBookingDtoStatusEnum::HONORED, $bookings->data->last()->status);
    }

    /**
     * A filter not available on `GET catalog/service-bookings` should not be sent.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([])));
        $paramBag = new ListServiceBookingRequestParamBag;
        $paramBag->setFilters(['seller_id' => 8, 'unknown_filter' => 'value']);

        (new OrigamiServiceBookingDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'        => ['seller_id' => '8'],
            'page'          => '1',
            'item_per_page' => '20',
        ]);
    }

    /**
     * `GET catalog/service-bookings` should only request the available includes, and read them.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockServiceBookingApiData([
                'id'    => 42,
                'slot'  => ['data' => $this->mockServiceSlotApiData(['id' => 7])],
                'offer' => ['data' => ['id' => 11, 'product_id' => 12, 'seller_id' => 8, 'product' => ['data' => [
                    'id' => 12, 'reference' => 'COUPE', 'translations' => ['data' => [['locale' => 'fr', 'language_id' => 1, 'default' => true, 'name' => 'Coupe femme']]],
                ]]]],
                'customer'         => ['data' => $this->mockServiceBookingCustomerApiData(['id' => 3])],
                'seller'           => ['data' => ['id' => 8, 'name' => 'Inès Moreau']],
                'state'            => ['data' => $this->mockStateApiData(['id' => 31])],
                'available_states' => ['data' => [$this->mockStateApiData(['id' => 29]), $this->mockStateApiData(['id' => 30])]],
                'order'            => ['data' => ['id' => 5, 'reference' => 'ORD-0005']],
            ]),
        ])));
        $paramBag = new ListServiceBookingRequestParamBag;
        $paramBag->setIncludes(['slot', 'offer.product', 'customer', 'seller', 'state', 'available_states', 'order', 'rangeSlots', 'unknown']);

        $bookings = (new OrigamiServiceBookingDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['slot', 'offer.product', 'customer', 'seller', 'state', 'available_states', 'order'],
        ]);
        $booking = $bookings->data->first();
        $this->assertInstanceOf(ServiceSlotDto::class, $booking->slot);
        $this->assertSame(7, $booking->slot->id);
        $this->assertSame('Coupe femme', $booking->offer->product->translations->first()->name);
        $this->assertInstanceOf(ServiceBookingCustomerDto::class, $booking->customer);
        $this->assertSame('Jane Doe', $booking->customer->user->name);
        $this->assertInstanceOf(ServiceBookingSellerDto::class, $booking->seller);
        $this->assertSame(8, $booking->seller->id);
        $this->assertInstanceOf(StateDto::class, $booking->state);
        $this->assertSame(31, $booking->state->id);
        $this->assertContainsOnlyInstancesOf(StateDto::class, $booking->availableStates);
        $this->assertSame([29, 30], $booking->availableStates->pluck('id')->all());
        $this->assertInstanceOf(ServiceBookingOrderDto::class, $booking->order);
        $this->assertSame('ORD-0005', $booking->order->reference);
    }

    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServiceBookingApiData(['id' => 42])])));

        $bookings = (new OrigamiServiceBookingDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'catalog/service-bookings');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $bookings->data->first()->id);
    }

    /**
     * `PATCH catalog/service-bookings/{id}` should send the state the booking moves to.
     */
    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());
        $paramBag = new UpdateServiceBookingRequestParamBag;
        $paramBag->stateId = 29;

        (new OrigamiServiceBookingDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'catalog/service-bookings/42');
        $this->assertLastApiRequestJsonWas(['state_id' => 29]);
    }
}
