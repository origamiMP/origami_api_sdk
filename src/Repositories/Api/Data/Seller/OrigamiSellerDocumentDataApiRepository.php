<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\DocumentTypeListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDocumentDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSellerDocumentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerDocumentDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws SellerDocumentDtoNotConstructableException
     */
    public function create(CreateSellerDocumentRequestParamBag $paramBag): SellerDocumentDto
    {
        $response = $this->restClient->post('sellers/documents', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new SellerDocumentDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws DocumentTypeListDtoNotConstructableException
     */
    public function getRequiredDocuments(int $sellerId): DocumentTypeListDto
    {
        $response = $this->restClient->get("sellers/$sellerId/required_documents");
        $responseContent = $this->decodeResponse($response);

        return new DocumentTypeListDto($responseContent);
    }
}
