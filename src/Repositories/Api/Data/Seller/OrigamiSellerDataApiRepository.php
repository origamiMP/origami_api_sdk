<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerLegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\ListSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerDataApiRepository extends OrigamiDataApiRepository
{
    public function list(ListSellerRequestParamBag $paramBag): SellerListDto
    {
        $response = $this->restClient->get('sellers', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new SellerListDto($responseContent);
    }

    public function get(int $id): SellerDto
    {
        $response = $this->restClient->get("sellers/$id");
        $responseContent = json_decode($response->getBody()->getContents());

        return new SellerDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    public function create(CreateSellerRequestParamBag $paramBag): SellerDto
    {
        $response = $this->restClient->post('sellers', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new SellerDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Get the list of legal types available for sellers
     */
    public function getLegalTypes(): SellerLegalTypeListDto
    {
        $response = $this->restClient->get('sellers/legal_types');
        $responseContent = json_decode($response->getBody()->getContents());

        return new SellerLegalTypeListDto($responseContent);
    }
}
