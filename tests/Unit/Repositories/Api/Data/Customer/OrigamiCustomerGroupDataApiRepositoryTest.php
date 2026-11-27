<?php

namespace Tests\Unit\Repositories\Api\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerGroupListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerGroupTranslationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\ListCustomerGroupRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Customer\OrigamiCustomerGroupDataApiRepository;
use Tests\TestCase;

class OrigamiCustomerGroupDataApiRepositoryTest extends TestCase
{
    /**
     * `GET customers/groups` should send the search, the pagination and the filters, and read each group of the page.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomerGroupApiData(['id' => 1]),
            $this->mockCustomerGroupApiData(['id' => 2, 'external_uid' => 'PRO']),
        ])));
        $paramBag = new ListCustomerGroupRequestParamBag;
        $paramBag->setSearch('pro');
        $paramBag->setPage(2);
        $paramBag->setFilters(['external_uid' => 'PRO']);

        $customerGroups = (new OrigamiCustomerGroupDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'customers/groups');
        $this->assertLastApiRequestQueryWas([
            'filter'        => ['external_uid' => 'PRO'],
            'page'          => '2',
            'item_per_page' => '20',
            'search'        => 'pro',
        ]);
        $this->assertInstanceOf(CustomerGroupListDto::class, $customerGroups);
        $this->assertSame([1, 2], $customerGroups->data->pluck('id')->all());
        $this->assertSame([null, 'PRO'], $customerGroups->data->pluck('externalUid')->all());
    }

    /**
     * `GET customers/groups` filtered on several ids, without pagination, should send the ids as a list and read the
     * groups (the response stays paginated).
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomerGroupApiData(['id' => 42]),
            $this->mockCustomerGroupApiData(['id' => 43]),
        ])));
        $paramBag = new ListCustomerGroupRequestParamBag;
        $paramBag->setFilters(['id' => [42, 43]]);
        $paramBag->setWithoutPagination(true);

        $customerGroups = (new OrigamiCustomerGroupDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'             => ['id' => ['42', '43']],
            'page'               => '1',
            'item_per_page'      => '20',
            'without_pagination' => '1',
        ]);
        $this->assertSame([42, 43], $customerGroups->data->pluck('id')->all());
    }

    /**
     * A filter not available on `GET customers/groups` should not be sent.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([])));
        $paramBag = new ListCustomerGroupRequestParamBag;
        $paramBag->setFilters(['id' => 42, 'name' => 'Professionals']);

        (new OrigamiCustomerGroupDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'        => ['id' => '42'],
            'page'          => '1',
            'item_per_page' => '20',
        ]);
    }

    /**
     * `GET customers/groups` should only request the available includes, and read the translations.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomerGroupApiData(['translations' => ['data' => [
                $this->mockCustomerGroupTranslationApiData(['id' => 42, 'name' => 'Professionals']),
            ]]]),
        ])));
        $paramBag = new ListCustomerGroupRequestParamBag;
        $paramBag->setIncludes(['translations', 'unknown']);

        $customerGroups = (new OrigamiCustomerGroupDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['translations'],
        ]);
        $translations = $customerGroups->data->first()->translations;
        $this->assertContainsOnlyInstancesOf(CustomerGroupTranslationDto::class, $translations);
        $this->assertSame([42], $translations->pluck('id')->all());
        $this->assertSame(['Professionals'], $translations->pluck('name')->all());
    }

    /**
     * `GET customers/groups` should be callable without param bag, then sending no query.
     */
    public function test_list_case_5(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomerGroupApiData(['id' => 42]),
        ])));

        $customerGroups = (new OrigamiCustomerGroupDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'customers/groups');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $customerGroups->data->first()->id);
        $this->assertFalse(isset($customerGroups->data->first()->translations));
    }
}
