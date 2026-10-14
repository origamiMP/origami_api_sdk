<?php

namespace Tests\Unit\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateBankAccountRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller\OrigamiSellerBankAccountDataApiRepository;
use Tests\TestCase;

class OrigamiSellerBankAccountDataApiRepositoryTest extends TestCase
{
    /**
     * `POST sellers/bank_accounts` should send the bank account as JSON, and read the created one from the `data`
     * key of the response.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerBankAccountApiData([
            'id'   => 42,
            'iban' => 'FR7630006000011234567890189',
        ])]));
        $paramBag = new CreateBankAccountRequestParamBag;
        $paramBag->bankName = 'Bank of Origami';
        $paramBag->ownerName = 'John Doe';
        $paramBag->ownerAddress = '10 rue de Rivoli, 75001 Paris';
        $paramBag->iban = 'FR7630006000011234567890189';
        $paramBag->bic = 'AGRIFRPP';
        $paramBag->isDefault = true;

        $bankAccount = (new OrigamiSellerBankAccountDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'sellers/bank_accounts');
        $this->assertLastApiRequestJsonWas([
            'bank_name'     => 'Bank of Origami',
            'owner_name'    => 'John Doe',
            'owner_address' => '10 rue de Rivoli, 75001 Paris',
            'iban'          => 'FR7630006000011234567890189',
            'bic'           => 'AGRIFRPP',
            'is_default'    => true,
        ]);
        $this->assertInstanceOf(SellerBankAccountDto::class, $bankAccount);
        $this->assertSame(42, $bankAccount->id);
        $this->assertSame('FR7630006000011234567890189', $bankAccount->iban);
    }
}
