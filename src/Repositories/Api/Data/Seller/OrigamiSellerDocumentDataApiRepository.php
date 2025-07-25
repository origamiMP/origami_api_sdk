<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\CreateDocumentResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateDocumentRequestParamBag;
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

    // TODO Onboarding seller : review
    /**
     * Create a new document for the seller
     */
    public function createDocument(CreateDocumentRequestParamBag $paramBag): CreateDocumentResponseDto
    {
        $response = $this->restClient->post('sellers/documents', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new CreateDocumentResponseDto($responseContent);
    }
}
