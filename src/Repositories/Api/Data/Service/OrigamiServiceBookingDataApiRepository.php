<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ConfirmServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\CreateServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\RescheduleServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\UpdateServiceBookingRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceBookingDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceBookingListDtoNotConstructableException
     */
    public function list(?ListServiceBookingRequestParamBag $paramBag = null): ServiceBookingListDto
    {
        $response = $this->restClient->get('catalog/service-bookings', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceBookingListDto($responseContent);
    }

    /**
     * Holds the places of a slot until the booking is confirmed or its hold expires (`holdDurationMinutes`, 15 by
     * default): the booking is pending, without order nor payment.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceBookingDtoNotConstructableException
     */
    public function create(CreateServiceBookingRequestParamBag $paramBag): ServiceBookingDto
    {
        $response = $this->restClient->post('catalog/service-bookings', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceBookingDto($this->getResponseContentData($responseContent));
    }

    /**
     * Moves the booking to one of its available states (`available_states` include), along the transitions of its
     * workflow. The API answers without content.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function update(int $id, UpdateServiceBookingRequestParamBag $paramBag): void
    {
        $this->restClient->patch("catalog/service-bookings/{$id}", $paramBag);
    }

    /**
     * Confirms a pending booking: its places stay booked for good.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceBookingDtoNotConstructableException
     */
    public function confirm(int $id, ?ConfirmServiceBookingRequestParamBag $paramBag = null): ServiceBookingDto
    {
        $response = $this->restClient->post("catalog/service-bookings/{$id}/confirm", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceBookingDto($this->getResponseContentData($responseContent));
    }

    /**
     * Moves a confirmed booking not begun yet to another slot of the same offer.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceBookingDtoNotConstructableException
     */
    public function reschedule(int $id, RescheduleServiceBookingRequestParamBag $paramBag): ServiceBookingDto
    {
        $response = $this->restClient->post("catalog/service-bookings/{$id}/reschedule", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceBookingDto($this->getResponseContentData($responseContent));
    }
}
