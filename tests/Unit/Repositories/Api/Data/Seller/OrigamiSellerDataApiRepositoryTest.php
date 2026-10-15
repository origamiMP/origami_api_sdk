<?php

namespace Tests\Unit\Repositories\Api\Data\Seller;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerLegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupLegalInformationDtoLegalTypeEnum;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerLegalInformationParamBagLegalTypeEnum;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller\CreateSellerParamBagTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerAddressParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerLegalInformationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSeller\CreateSellerTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\GetSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\ListSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller\OrigamiSellerDataApiRepository;
use Tests\TestCase;

class OrigamiSellerDataApiRepositoryTest extends TestCase
{
    /**
     * `GET sellers` should send the filters, pagination and search params as query, and read the paginated list
     * answered.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockSellerApiData(['id' => 1]),
            $this->mockSellerApiData(['id' => 2]),
        ])));
        $paramBag = new ListSellerRequestParamBag;
        $paramBag->withFilters(['address_city' => 'Paris']);
        $paramBag->page(3);
        $paramBag->search('john');

        $sellers = (new OrigamiSellerDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'sellers');
        $this->assertLastApiRequestQueryWas([
            'filter'        => ['address_city' => 'Paris'],
            'page'          => '3',
            'item_per_page' => '20',
            'search'        => 'john',
        ]);
        $this->assertInstanceOf(SellerListDto::class, $sellers);
        $this->assertSame([1, 2], $sellers->data->pluck('id')->all());
    }

    /**
     * A filter not available on `GET sellers` should not be sent.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([])));
        $paramBag = new ListSellerRequestParamBag;
        $paramBag->withFilters(['address_city' => 'Paris', 'unknown_filter' => 'value']);

        (new OrigamiSellerDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'        => ['address_city' => 'Paris'],
            'page'          => '1',
            'item_per_page' => '20',
        ]);
    }

    /**
     * `GET sellers/{id}` should read the seller from the `data` key of the response.
     */
    public function test_get_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerApiData(['id' => 42])]));

        $seller = (new OrigamiSellerDataApiRepository($restClient))->get(42);

        $this->assertLastApiRequestWas('GET', 'sellers/42');
        $this->assertInstanceOf(SellerDto::class, $seller);
        $this->assertSame(42, $seller->id);
    }

    /**
     * `POST sellers` should send the seller as JSON, its nested param bags (address, legal information,
     * translations) included, with enums sent as their value and dates in the format the API expects.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerApiData(['id' => 42])]));

        $address = new CreateSellerAddressParamBag;
        $address->addressLine1 = '10 rue de Rivoli';
        $address->addressLine2 = '';
        $address->postcode = '75001';
        $address->city = 'Paris';
        $address->countryIso = 'FR';

        $legalInformation = new CreateSellerLegalInformationParamBag;
        $legalInformation->legalType = CreateSellerLegalInformationParamBagLegalTypeEnum::INDIVIDUAL;
        $legalInformation->managerFirstName = 'John';
        $legalInformation->managerLastName = 'Doe';
        $legalInformation->managerBirthday = Carbon::parse('1990-01-31 12:00:00');
        $legalInformation->managerCountryIso = 'FR';
        $legalInformation->managerNationalityIso = 'FR';

        $translation = new CreateSellerTranslationParamBag;
        $translation->languageId = 1;
        $translation->description = 'Description';

        $paramBag = new CreateSellerRequestParamBag;
        $paramBag->firstname = 'John';
        $paramBag->lastname = 'Doe';
        $paramBag->email = 'john@doe.com';
        $paramBag->sellerType = CreateSellerParamBagTypeEnum::MARKETPLACE;
        $paramBag->holidayDepartureDate = Carbon::parse('2024-07-01 08:00:00');
        $paramBag->address = $address;
        $paramBag->legalInformation = $legalInformation;
        $paramBag->translations = [$translation];

        $seller = (new OrigamiSellerDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'sellers');
        $this->assertLastApiRequestJsonWas([
            'firstname'              => 'John',
            'lastname'               => 'Doe',
            'email'                  => 'john@doe.com',
            'seller_type'            => 'marketplace',
            'state'                  => 'VALIDATED',
            'holiday_departure_date' => '2024-07-01',
            'address'                => [
                'address_line1' => '10 rue de Rivoli',
                'address_line2' => '',
                'postcode'      => '75001',
                'city'          => 'Paris',
                'country_iso'   => 'FR',
            ],
            'legal_information' => [
                'legal_type'              => 'INDIVIDUAL',
                'manager_first_name'      => 'John',
                'manager_last_name'       => 'Doe',
                'manager_birthday'        => '1990-01-31',
                'manager_country_iso'     => 'FR',
                'manager_nationality_iso' => 'FR',
            ],
            'translations' => [
                ['language_id' => 1, 'description' => 'Description'],
            ],
        ]);
        $this->assertInstanceOf(SellerDto::class, $seller);
        $this->assertSame(42, $seller->id);
    }

    /**
     * `GET sellers/legal_types` answers a flat list of legal types.
     */
    public function test_get_legal_types_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse([
            UserGroupLegalInformationDtoLegalTypeEnum::COMPANY->value,
            UserGroupLegalInformationDtoLegalTypeEnum::INDIVIDUAL->value,
        ]));

        $legalTypes = (new OrigamiSellerDataApiRepository($restClient))->getLegalTypes();

        $this->assertLastApiRequestWas('GET', 'sellers/legal_types');
        $this->assertInstanceOf(SellerLegalTypeListDto::class, $legalTypes);
        $this->assertSame(['COMPANY', 'INDIVIDUAL'], $legalTypes->types);
    }

    public function test_activate_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiSellerDataApiRepository($restClient))->activate(42);

        $this->assertLastApiRequestWas('POST', 'sellers/42/activate');
        $this->assertLastApiRequestHadNoBody();
    }

    public function test_deactivate_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiSellerDataApiRepository($restClient))->deactivate(42);

        $this->assertLastApiRequestWas('POST', 'sellers/42/deactivate');
        $this->assertLastApiRequestHadNoBody();
    }

    /**
     * `GET sellers` should send the includes available on a seller as query (the commented ones being dropped), and
     * read the included bank accounts and required documents of each seller.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockSellerApiData([
                'bank_accounts'      => ['data' => [$this->mockSellerBankAccountApiData(['id' => 42])]],
                'required_documents' => ['data' => [$this->mockDocumentTypeApiData(['id' => 43])]],
            ]),
        ])));
        $paramBag = new ListSellerRequestParamBag;
        $paramBag->setIncludes(['bank_accounts', 'required_documents', 'warehouses', 'unknown']);

        $sellers = (new OrigamiSellerDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'sellers');
        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['bank_accounts', 'required_documents'],
        ]);
        $this->assertInstanceOf(SellerListDto::class, $sellers);

        $seller = $sellers->data->first();
        $this->assertInstanceOf(SellerBankAccountDto::class, $seller->bankAccounts->first());
        $this->assertSame(42, $seller->bankAccounts->first()->id);
        $this->assertInstanceOf(DocumentTypeDto::class, $seller->requiredDocuments->first());
        $this->assertSame(43, $seller->requiredDocuments->first()->id);
    }

    /**
     * `GET sellers/{id}` should send the includes available on a seller as query, and read the included documents,
     * default tax and users.
     */
    public function test_get_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerApiData([
            'documents'   => ['data' => [$this->mockSellerDocumentApiData(['id' => 42])]],
            'default_tax' => ['data' => $this->mockTaxApiData(['id' => 43])],
            'users'       => ['data' => [$this->mockUserApiData(['id' => 44])]],
        ])]));
        $paramBag = new GetSellerRequestParamBag;
        $paramBag->setIncludes(['documents', 'default_tax', 'users', 'reviews']);

        $seller = (new OrigamiSellerDataApiRepository($restClient))->get(1, $paramBag);

        $this->assertLastApiRequestWas('GET', 'sellers/1');
        $this->assertLastApiRequestQueryWas(['include' => ['documents', 'default_tax', 'users']]);
        $this->assertInstanceOf(SellerDto::class, $seller);
        $this->assertInstanceOf(SellerDocumentDto::class, $seller->documents->first());
        $this->assertSame(42, $seller->documents->first()->id);
        $this->assertInstanceOf(TaxDto::class, $seller->defaultTax);
        $this->assertSame(43, $seller->defaultTax->id);
        $this->assertInstanceOf(UserDto::class, $seller->users->first());
        $this->assertSame(44, $seller->users->first()->id);
    }

    /**
     * `POST sellers` should send the includes available on a seller as query, along with the seller as JSON, and read
     * the included users of the created seller.
     */
    public function test_create_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerApiData([
            'users' => ['data' => [$this->mockUserApiData(['id' => 42])]],
        ])]));
        $paramBag = new CreateSellerRequestParamBag;
        $paramBag->email = 'john@doe.com';
        $paramBag->setIncludes(['users', 'unknown']);

        $seller = (new OrigamiSellerDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'sellers');
        $this->assertLastApiRequestQueryWas(['include' => ['users']]);
        $this->assertInstanceOf(SellerDto::class, $seller);
        $this->assertSame(42, $seller->users->first()->id);
    }

    /**
     * `POST sellers` should send both holiday dates without their time, in the format the API expects.
     */
    public function test_create_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockSellerApiData()]));
        $paramBag = new CreateSellerRequestParamBag;
        $paramBag->holidayDepartureDate = Carbon::parse('2024-07-01 08:00:00');
        $paramBag->holidayReturnDate = Carbon::parse('2024-07-31 18:00:00');

        (new OrigamiSellerDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestJsonWas([
            'state'                  => 'VALIDATED',
            'holiday_departure_date' => '2024-07-01',
            'holiday_return_date'    => '2024-07-31',
        ]);
    }

    /**
     * `GET sellers` should be callable without param bag, then sending no query.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockSellerApiData(['id' => 42])])));

        $sellers = (new OrigamiSellerDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'sellers');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertInstanceOf(SellerListDto::class, $sellers);
        $this->assertSame(42, $sellers->data->first()->id);
    }
}
