<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop\PrestashopOrderStateListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop\PrestashopShippingZoneListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\Prestashop\PrestashopTaxListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnprocessableEntityException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopOrderStateListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopShippingZoneListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\Prestashop\PrestashopTaxListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetPrestashopOrderStatesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetPrestashopShippingZonesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetPrestashopTaxesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

/**
 * Data read live from a PrestaShop shop connected to the PrestaShop module, identified by its shop id (the `shop_id`
 * of one of the module configurations). When the shop cannot be reached, or answers an error, the API answers a 422.
 */
class OrigamiPrestashopDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * The order states of the shop, named in its default language.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws PrestashopOrderStateListDtoNotConstructableException
     * @throws OrigamiApiUnprocessableEntityException
     */
    public function getOrderStates(GetPrestashopOrderStatesRequestParamBag $paramBag): PrestashopOrderStateListDto
    {
        $response = $this->restClient->get('modules/prestashop/order_states', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new PrestashopOrderStateListDto($responseContent);
    }

    /**
     * The shipping zones of the shop, active or not.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws PrestashopShippingZoneListDtoNotConstructableException
     * @throws OrigamiApiUnprocessableEntityException
     */
    public function getShippingZones(GetPrestashopShippingZonesRequestParamBag $paramBag): PrestashopShippingZoneListDto
    {
        $response = $this->restClient->get('modules/prestashop/shipping_zones', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new PrestashopShippingZoneListDto($responseContent);
    }

    /**
     * The taxes of the shop.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws PrestashopTaxListDtoNotConstructableException
     * @throws OrigamiApiUnprocessableEntityException
     */
    public function getTaxes(GetPrestashopTaxesRequestParamBag $paramBag): PrestashopTaxListDto
    {
        $response = $this->restClient->get('modules/prestashop/taxes', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new PrestashopTaxListDto($responseContent);
    }
}
