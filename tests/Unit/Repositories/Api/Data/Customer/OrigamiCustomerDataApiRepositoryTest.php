<?php

namespace Tests\Unit\Repositories\Api\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerGroupDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\CreateCustomer\CreateCustomerUserParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\CreateCustomerRequestParamBag;
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

    /**
     * `GET customers` should read the `customer_groups` include, with the default flag of each group.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomerApiData(['customer_groups' => ['data' => [
                $this->mockCustomerGroupApiData(['id' => 42, 'is_default' => 1]),
                $this->mockCustomerGroupApiData(['id' => 43, 'is_default' => 0]),
            ]]]),
        ])));
        $paramBag = new ListCustomerRequestParamBag;
        $paramBag->setIncludes(['customer_groups']);

        $customers = (new OrigamiCustomerDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['customer_groups'],
        ]);
        $customerGroups = $customers->data->first()->customerGroups;
        $this->assertContainsOnlyInstancesOf(CustomerGroupDto::class, $customerGroups);
        $this->assertSame([42, 43], $customerGroups->pluck('id')->all());
        $this->assertSame([true, false], $customerGroups->pluck('isDefault')->all());
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

    /**
     * `POST customers` should request the `customer_groups` include, and read it.
     */
    public function test_create_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockCustomerApiData([
            'customer_groups' => ['data' => [$this->mockCustomerGroupApiData(['id' => 42, 'is_default' => 1])]],
        ])]));
        $paramBag = new CreateCustomerRequestParamBag;
        $paramBag->firstName = 'Jane';
        $paramBag->lastName = 'Doe';
        $paramBag->email = 'jane@doe.com';
        $paramBag->setIncludes(['customer_groups']);

        $customer = (new OrigamiCustomerDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestQueryWas(['include' => ['customer_groups']]);
        $this->assertContainsOnlyInstancesOf(CustomerGroupDto::class, $customer->customerGroups);
        $this->assertSame([42], $customer->customerGroups->pluck('id')->all());
    }
}
