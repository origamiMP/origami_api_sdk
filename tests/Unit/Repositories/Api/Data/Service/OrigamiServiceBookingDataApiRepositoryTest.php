<?php

namespace Tests\Unit\Repositories\Api\Data\Service;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingCustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingOrderDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingSellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceSlotDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoOriginEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service\ServiceBookingDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Service\CreateServiceBookingParamBagOriginEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ConfirmServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\CreateServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\RescheduleServiceBookingRequestParamBag;
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
                'offer' => ['data' => $this->mockProductOfferApiData(['id' => 11, 'product' => ['data' => $this->mockProductApiData([
                    'id' => 12, 'translations' => ['data' => [$this->mockProductTranslationApiData(['locale' => 'fr', 'name' => 'Coupe femme'])]],
                ])]])],
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
            'include'       => ['slot', 'offer.product', 'customer', 'seller', 'state', 'available_states', 'order', 'rangeSlots'],
        ]);
        $booking = $bookings->data->first();
        $this->assertInstanceOf(ServiceSlotDto::class, $booking->slot);
        $this->assertSame(7, $booking->slot->id);
        $this->assertSame('Coupe femme', $booking->offer->product->translations->first()->name);
        $this->assertSame(75.0, $booking->offer->priceTaxInc);
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
     * `POST catalog/service-bookings` should send the slot, the offer and the user the operator books for, and read the
     * pending booking.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceBookingApiData([
            'id'     => 42,
            'status' => ServiceBookingDtoStatusEnum::PENDING->value,
            'origin' => ServiceBookingDtoOriginEnum::STAFF->value,
        ])]));
        $paramBag = new CreateServiceBookingRequestParamBag;
        $paramBag->serviceSlotId = 21;
        $paramBag->productOfferId = 11;
        $paramBag->userId = 7;

        $booking = (new OrigamiServiceBookingDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'catalog/service-bookings');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertLastApiRequestJsonWas(['service_slot_id' => 21, 'product_offer_id' => 11, 'user_id' => 7]);
        $this->assertInstanceOf(ServiceBookingDto::class, $booking);
        $this->assertSame(42, $booking->id);
        $this->assertSame(ServiceBookingDtoStatusEnum::PENDING, $booking->status);
    }

    /**
     * The days of a planning by day range should be sent as dates, with the origin, the quantity and the hold.
     */
    public function test_create_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceBookingApiData(['id' => 42])]));
        $paramBag = new CreateServiceBookingRequestParamBag;
        $paramBag->servicePlanningId = 9;
        $paramBag->startsOn = Carbon::parse('2024-05-12 16:12:56');
        $paramBag->endsOn = Carbon::parse('2024-05-14');
        $paramBag->quantity = 2;
        $paramBag->holdDurationMinutes = 30;
        $paramBag->origin = CreateServiceBookingParamBagOriginEnum::SELLER_LINK;
        $paramBag->originDetail = 'newsletter';
        $paramBag->reference = 'RES-0042';

        (new OrigamiServiceBookingDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestJsonWas([
            'service_planning_id'   => 9,
            'starts_on'             => '2024-05-12',
            'ends_on'               => '2024-05-14',
            'quantity'              => 2,
            'reference'             => 'RES-0042',
            'hold_duration_minutes' => 30,
            'origin'                => 'seller_link',
            'origin_detail'         => 'newsletter',
        ]);
    }

    /**
     * `POST catalog/service-bookings` should only request the available includes, and read them.
     */
    public function test_create_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceBookingApiData([
            'id'       => 42,
            'customer' => ['data' => $this->mockServiceBookingCustomerApiData(['id' => 3])],
        ])]));
        $paramBag = new CreateServiceBookingRequestParamBag;
        $paramBag->serviceSlotId = 21;
        $paramBag->productOfferId = 11;
        $paramBag->setIncludes(['customer', 'unknown']);

        $booking = (new OrigamiServiceBookingDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestQueryWas(['include' => ['customer']]);
        $this->assertInstanceOf(ServiceBookingCustomerDto::class, $booking->customer);
        $this->assertSame(3, $booking->customer->id);
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

    /**
     * `POST catalog/service-bookings/{id}/confirm` without order should send nothing, and read the confirmed booking.
     */
    public function test_confirm_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceBookingApiData(['id' => 42])]));

        $booking = (new OrigamiServiceBookingDataApiRepository($restClient))->confirm(42);

        $this->assertLastApiRequestWas('POST', 'catalog/service-bookings/42/confirm');
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(ServiceBookingDto::class, $booking);
        $this->assertSame(ServiceBookingDtoStatusEnum::CONFIRMED, $booking->status);
    }

    /**
     * The order line and the reference of the storefront order should be sent, and the available includes requested.
     */
    public function test_confirm_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceBookingApiData([
            'id'   => 42,
            'slot' => ['data' => $this->mockServiceSlotApiData(['id' => 21])],
        ])]));
        $paramBag = new ConfirmServiceBookingRequestParamBag;
        $paramBag->orderLineId = 5;
        $paramBag->reference = 'ORD-0005';
        $paramBag->setIncludes(['slot', 'unknown']);

        $booking = (new OrigamiServiceBookingDataApiRepository($restClient))->confirm(42, $paramBag);

        $this->assertLastApiRequestQueryWas(['include' => ['slot']]);
        $this->assertLastApiRequestJsonWas(['order_line_id' => 5, 'reference' => 'ORD-0005']);
        $this->assertSame(21, $booking->slot->id);
    }

    /**
     * `POST catalog/service-bookings/{id}/reschedule` should send the slot the booking moves to.
     */
    public function test_reschedule_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceBookingApiData(['id' => 42, 'service_slot_id' => 22])]));
        $paramBag = new RescheduleServiceBookingRequestParamBag;
        $paramBag->serviceSlotId = 22;

        $booking = (new OrigamiServiceBookingDataApiRepository($restClient))->reschedule(42, $paramBag);

        $this->assertLastApiRequestWas('POST', 'catalog/service-bookings/42/reschedule');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertLastApiRequestJsonWas(['service_slot_id' => 22]);
        $this->assertSame(22, $booking->serviceSlotId);
    }

    /**
     * `POST catalog/service-bookings/{id}/reschedule` should only request the available includes, and read them.
     */
    public function test_reschedule_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockServiceBookingApiData([
            'id'   => 42,
            'slot' => ['data' => $this->mockServiceSlotApiData(['id' => 22])],
        ])]));
        $paramBag = new RescheduleServiceBookingRequestParamBag;
        $paramBag->serviceSlotId = 22;
        $paramBag->setIncludes(['slot', 'unknown']);

        $booking = (new OrigamiServiceBookingDataApiRepository($restClient))->reschedule(42, $paramBag);

        $this->assertLastApiRequestQueryWas(['include' => ['slot']]);
        $this->assertSame(22, $booking->slot->id);
    }
}
