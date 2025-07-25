<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\CreateBankAccountResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\CreateDocumentResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\LegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\RequiredDocumentListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateBankAccountRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateDocumentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerDataApiRepository extends OrigamiDataApiRepository
{
    // TODO Onboarding seller : review
    /**
     * Get the list of legal types available for sellers
     */
    public function getLegalTypes(): LegalTypeListDto
    {
        $response = $this->restClient->get('sellers/legal_types');
        $responseContent = json_decode($response->getBody()->getContents());

        return new LegalTypeListDto($responseContent);
    }

    // TODO Onboarding seller : review
    /**
     * Create a new bank account for the seller
     */
    public function createBankAccount(CreateBankAccountRequestParamBag $paramBag): CreateBankAccountResponseDto
    {
        $response = $this->restClient->post('sellers/bank_accounts', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new CreateBankAccountResponseDto($responseContent);
    }

    // TODO Onboarding seller : review
    /**
     * Get the list of required documents for a specific seller
     */
    public function getRequiredDocuments(int $sellerId): RequiredDocumentListDto
    {
        $response = $this->restClient->get("sellers/{$sellerId}/required_documents");
        $responseContent = json_decode($response->getBody()->getContents());

        return new RequiredDocumentListDto($responseContent);
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
