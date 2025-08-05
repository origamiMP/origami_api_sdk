<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSellerDocumentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerDocumentDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Get the list of required documents for a specific seller
     */
    public function getRequiredDocuments(int $sellerId): DocumentTypeListDto
    {
        $response = $this->restClient->get("sellers/{$sellerId}/required_documents");
        $responseContent = json_decode($response->getBody()->getContents());

        return new DocumentTypeListDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Create a new document for the seller
     */
    public function create(CreateSellerDocumentRequestParamBag $paramBag): SellerDocumentDto
    {
        $response = $this->restClient->post('sellers/documents', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new SellerDocumentDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }
}
