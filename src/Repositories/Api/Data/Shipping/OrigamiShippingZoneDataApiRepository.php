<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Shipping;

use OrigamiMp\OrigamiApiSdk\Dtos\Shipping\ShippingZoneListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Shipping\ShippingZoneListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Shipping\ListShippingZoneRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiShippingZoneDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * The search looks into the names of the zones, in the language of the request.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ShippingZoneListDtoNotConstructableException
     */
    public function list(?ListShippingZoneRequestParamBag $paramBag = null): ShippingZoneListDto
    {
        $response = $this->restClient->get('shipping/zones', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ShippingZoneListDto($responseContent);
    }
}
