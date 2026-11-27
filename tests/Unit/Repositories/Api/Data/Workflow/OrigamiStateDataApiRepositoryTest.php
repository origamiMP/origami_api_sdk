<?php

namespace Tests\Unit\Repositories\Api\Data\Workflow;

use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateTranslationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Workflow\ListStateRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Workflow\OrigamiStateDataApiRepository;
use Tests\TestCase;

class OrigamiStateDataApiRepositoryTest extends TestCase
{
    /**
     * `GET states` should send the search, the pagination and the filters, and read each state of the page.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockStateApiData(['id' => 1, 'key' => 'order_seller_pending', 'initial' => true]),
            $this->mockStateApiData(['id' => 2, 'key' => 'order_seller_accepted']),
        ])));
        $paramBag = new ListStateRequestParamBag;
        $paramBag->setSearch('order');
        $paramBag->setPage(2);
        $paramBag->setFilters(['workflow_type' => 'order_seller']);

        $states = (new OrigamiStateDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'states');
        $this->assertLastApiRequestQueryWas([
            'filter'        => ['workflow_type' => 'order_seller'],
            'page'          => '2',
            'item_per_page' => '20',
            'search'        => 'order',
        ]);
        $this->assertInstanceOf(StateListDto::class, $states);
        $this->assertSame([1, 2], $states->data->pluck('id')->all());
        $this->assertSame(['order_seller_pending', 'order_seller_accepted'], $states->data->pluck('key')->all());
        $this->assertSame([true, false], $states->data->pluck('initial')->all());
    }

    /**
     * `GET states` filtered on several ids, without pagination, should send the ids as a list and read the states
     * (the response stays paginated).
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockStateApiData(['id' => 42]),
            $this->mockStateApiData(['id' => 43]),
        ])));
        $paramBag = new ListStateRequestParamBag;
        $paramBag->setFilters(['id' => [42, 43]]);
        $paramBag->setWithoutPagination(true);

        $states = (new OrigamiStateDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'             => ['id' => ['42', '43']],
            'page'               => '1',
            'item_per_page'      => '20',
            'without_pagination' => '1',
        ]);
        $this->assertSame([42, 43], $states->data->pluck('id')->all());
    }

    /**
     * A filter not available on `GET states` should not be sent.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([])));
        $paramBag = new ListStateRequestParamBag;
        $paramBag->setFilters(['workflow_id' => 42, 'color' => '#2e7d32']);

        (new OrigamiStateDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'        => ['workflow_id' => '42'],
            'page'          => '1',
            'item_per_page' => '20',
        ]);
    }

    /**
     * `GET states` should only request the available includes, and read the translations.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockStateApiData(['translations' => ['data' => [
                $this->mockStateTranslationApiData(['locale' => 'en', 'name' => 'Confirmed']),
                $this->mockStateTranslationApiData(['locale' => 'fr', 'name' => 'Confirmé', 'default' => false]),
            ]]]),
        ])));
        $paramBag = new ListStateRequestParamBag;
        $paramBag->setIncludes(['translations', 'workflow']);

        $states = (new OrigamiStateDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['translations'],
        ]);
        $translations = $states->data->first()->translations;
        $this->assertContainsOnlyInstancesOf(StateTranslationDto::class, $translations);
        $this->assertSame(['en', 'fr'], $translations->pluck('locale')->all());
        $this->assertSame(['Confirmed', 'Confirmé'], $translations->pluck('name')->all());
        $this->assertSame([true, false], $translations->pluck('default')->all());
    }

    /**
     * `GET states` should be callable without param bag, then sending no query.
     */
    public function test_list_case_5(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockStateApiData(['id' => 42]),
        ])));

        $states = (new OrigamiStateDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'states');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $states->data->first()->id);
        $this->assertFalse(isset($states->data->first()->translations));
    }
}
