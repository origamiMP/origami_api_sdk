<?php

namespace Tests\Unit\Repositories\Api\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Payment\PaymentPspCardDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\CreateCustomer\CreateCustomerUserParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\CreateCustomerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\GetCustomerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\ListCustomerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Customer\OrigamiCustomerDataApiRepository;
use Tests\TestCase;

class OrigamiCustomerDataApiRepositoryTest extends TestCase
{
    /**
     * `GET customers` should send the search and the pagination.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomerApiData(['id' => 1]),
            $this->mockCustomerApiData(['id' => 2, 'phone' => null]),
        ])));
        $paramBag = new ListCustomerRequestParamBag;
        $paramBag->setSearch('jane');
        $paramBag->setPerPage(10);

        $customers = (new OrigamiCustomerDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'customers');
        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '10',
            'search'        => 'jane',
        ]);
        $this->assertInstanceOf(CustomerListDto::class, $customers);
        $this->assertSame([1, 2], $customers->data->pluck('id')->all());
        $this->assertSame('+33612345678', $customers->data->first()->phone);
        $this->assertNull($customers->data->last()->phone);
    }

    /**
     * `GET customers` should only request the available includes, and read them.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomerApiData(['id' => 42, 'users' => ['data' => [$this->mockUserApiData(['id' => 7])]]]),
        ])));
        $paramBag = new ListCustomerRequestParamBag;
        $paramBag->setIncludes(['users', 'tickets', 'unknown']);

        $customers = (new OrigamiCustomerDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['users'],
        ]);
        $customer = $customers->data->first();
        $this->assertContainsOnlyInstancesOf(UserDto::class, $customer->users);
        $this->assertSame([7], $customer->users->pluck('id')->all());
    }

    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockCustomerApiData(['id' => 42])])));

        $customers = (new OrigamiCustomerDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'customers');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $customers->data->first()->id);
        $this->assertFalse(isset($customers->data->first()->users));
    }

    public function test_get_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockCustomerApiData(['id' => 42])]));

        $customer = (new OrigamiCustomerDataApiRepository($restClient))->get(42);

        $this->assertLastApiRequestWas('GET', 'customers/42');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertInstanceOf(CustomerDto::class, $customer);
        $this->assertSame(42, $customer->id);
        $this->assertFalse(isset($customer->pspCards));
    }

    /**
     * `GET customers/{id}` should only request the available includes, and read the saved cards with their brand and
     * last digits.
     */
    public function test_get_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockCustomerApiData([
            'id'        => 42,
            'psp_cards' => ['data' => [$this->mockPaymentPspCardApiData(['id' => 7])]],
        ])]));
        $paramBag = new GetCustomerRequestParamBag;
        $paramBag->setIncludes(['psp_cards', 'psp_wallets', 'unknown']);

        $customer = (new OrigamiCustomerDataApiRepository($restClient))->get(42, $paramBag);

        $this->assertLastApiRequestQueryWas(['include' => ['psp_cards']]);
        $this->assertContainsOnlyInstancesOf(PaymentPspCardDto::class, $customer->pspCards);
        $card = $customer->pspCards->first();
        $this->assertSame(7, $card->id);
        $this->assertSame('visa', $card->brand);
        $this->assertSame('4242', $card->last4);
    }

    /**
     * `POST customers` should send the customer and the user created with it, and request the includes as query.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockCustomerApiData([
            'id'    => 42,
            'users' => ['data' => [$this->mockUserApiData(['id' => 7])]],
        ])]));
        $user = new CreateCustomerUserParamBag;
        $user->firstName = 'Jane';
        $user->lastName = 'Doe';
        $user->email = 'jane@doe.com';
        $user->password = 'Some-Password-42';
        $paramBag = new CreateCustomerRequestParamBag;
        $paramBag->firstName = 'Jane';
        $paramBag->lastName = 'Doe';
        $paramBag->email = 'jane@doe.com';
        $paramBag->phone = '+33612345678';
        $paramBag->user = $user;
        $paramBag->setIncludes(['users']);

        $customer = (new OrigamiCustomerDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'customers');
        $this->assertLastApiRequestQueryWas(['include' => ['users']]);
        $this->assertLastApiRequestJsonWas([
            'firstname' => 'Jane',
            'lastname'  => 'Doe',
            'email'     => 'jane@doe.com',
            'phone'     => '+33612345678',
            'user'      => ['firstname' => 'Jane', 'lastname' => 'Doe', 'email' => 'jane@doe.com', 'password' => 'Some-Password-42'],
        ]);
        $this->assertInstanceOf(CustomerDto::class, $customer);
        $this->assertSame(42, $customer->id);
        $this->assertSame([7], $customer->users->pluck('id')->all());
    }

    /**
     * A customer created without user, phone nor language should not send them.
     */
    public function test_create_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockCustomerApiData(['id' => 42])]));
        $paramBag = new CreateCustomerRequestParamBag;
        $paramBag->firstName = 'Jane';
        $paramBag->lastName = 'Doe';
        $paramBag->email = 'jane@doe.com';

        (new OrigamiCustomerDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestQueryWas([]);
        $this->assertLastApiRequestJsonWas(['firstname' => 'Jane', 'lastname' => 'Doe', 'email' => 'jane@doe.com']);
    }
}
