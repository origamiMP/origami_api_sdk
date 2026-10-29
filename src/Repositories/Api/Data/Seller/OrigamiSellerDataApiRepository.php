<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerLegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerLegalTypeListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\GetSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\ListSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws SellerListDtoNotConstructableException
     */
    public function list(?ListSellerRequestParamBag $paramBag = null): SellerListDto
    {
        $response = $this->restClient->get('sellers', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new SellerListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws SellerDtoNotConstructableException
     */
    public function get(int $id, ?GetSellerRequestParamBag $paramBag = null): SellerDto
    {
        $response = $this->restClient->get("sellers/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new SellerDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws SellerDtoNotConstructableException
     */
    public function create(CreateSellerRequestParamBag $paramBag): SellerDto
    {
        $response = $this->restClient->post('sellers', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new SellerDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws SellerLegalTypeListDtoNotConstructableException
     */
    public function getLegalTypes(): SellerLegalTypeListDto
    {
        $response = $this->restClient->get('sellers/legal_types');
        $responseContent = $this->decodeListResponse($response);

        return new SellerLegalTypeListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function activate(int $id): void
    {
        $this->restClient->post("sellers/{$id}/activate");
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function deactivate(int $id): void
    {
        $this->restClient->post("sellers/{$id}/deactivate");
    }
}
