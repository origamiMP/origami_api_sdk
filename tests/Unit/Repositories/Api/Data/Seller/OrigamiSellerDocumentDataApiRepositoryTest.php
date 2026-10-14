<?php

namespace Tests\Unit\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSellerDocumentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller\OrigamiSellerDocumentDataApiRepository;
use Tests\TestCase;

class OrigamiSellerDocumentDataApiRepositoryTest extends TestCase
{
    /**
     * `GET sellers/{id}/required_documents` answers a non-paginated list: each of its items should be read as a
     * document type.
     */
    public function test_get_required_documents_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockDocumentTypeApiData(['key' => 'id_card']),
            $this->mockDocumentTypeApiData(['key' => 'kbis']),
        ]]));

        $documentTypes = (new OrigamiSellerDocumentDataApiRepository($restClient))->getRequiredDocuments(42);

        $this->assertLastApiRequestWas('GET', 'sellers/42/required_documents');
        $this->assertInstanceOf(DocumentTypeListDto::class, $documentTypes);
        $this->assertSame(['id_card', 'kbis'], $documentTypes->data->pluck('key')->all());
    }

    /**
     * `POST sellers/documents` should send the document as JSON, and read the created one from the `data` key of
     * the response.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerDocumentApiData([
            'id'   => 42,
            'name' => 'id_card.pdf',
        ])]));
        $paramBag = new CreateSellerDocumentRequestParamBag;
        $paramBag->content = 'YmFzZTY0';
        $paramBag->documentTypeId = 3;
        $paramBag->name = 'id_card.pdf';
        $paramBag->userGroupId = 7;

        $document = (new OrigamiSellerDocumentDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'sellers/documents');
        $this->assertLastApiRequestJsonWas([
            'content'          => 'YmFzZTY0',
            'pages'            => [],
            'document_type_id' => 3,
            'name'             => 'id_card.pdf',
            'user_group_id'    => 7,
        ]);
        $this->assertInstanceOf(SellerDocumentDto::class, $document);
        $this->assertSame(42, $document->id);
        $this->assertSame('id_card.pdf', $document->name);
    }

    /**
     * `POST sellers/documents` should send the includes available on a document as query (the commented ones being
     * dropped), along with the document as JSON, and read the included document type.
     */
    public function test_create_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerDocumentApiData([
            'document_type' => ['data' => $this->mockDocumentTypeApiData(['id' => 42])],
        ])]));
        $paramBag = new CreateSellerDocumentRequestParamBag;
        $paramBag->content = 'YmFzZTY0';
        $paramBag->documentTypeId = 42;
        $paramBag->name = 'id_card.pdf';
        $paramBag->setIncludes(['document_type', 'payment_psp_documents']);

        $document = (new OrigamiSellerDocumentDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'sellers/documents');
        $this->assertLastApiRequestQueryWas(['include' => ['document_type']]);
        $this->assertInstanceOf(SellerDocumentDto::class, $document);
        $this->assertInstanceOf(DocumentTypeDto::class, $document->documentType);
        $this->assertSame(42, $document->documentType->id);
    }
}
