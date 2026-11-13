<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServiceBookingRequestParamBag;
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
}
