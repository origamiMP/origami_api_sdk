<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\CreateBankAccountResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\LegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\RequiredDocumentListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateBankAccountRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Get the list of legal types available for sellers
     */
    public function getLegalTypes(): LegalTypeListDto
    {
        $response = $this->restClient->get('sellers/legal_types');
        $responseContent = json_decode($response->getBody()->getContents());

        return new LegalTypeListDto($responseContent);
    }

    /**
     * Create a new bank account for the seller
     */
    public function createBankAccount(CreateBankAccountRequestParamBag $paramBag): CreateBankAccountResponseDto
    {
        $response = $this->restClient->post('sellers/bank_accounts', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new CreateBankAccountResponseDto($responseContent);
    }

    /**
     * Get the list of required documents for a specific seller
     */
    public function getRequiredDocuments(int $sellerId): RequiredDocumentListDto
    {
        $response = $this->restClient->get("sellers/{$sellerId}/required_documents");
        $responseContent = json_decode($response->getBody()->getContents());

        return new RequiredDocumentListDto($responseContent);
    }
}
