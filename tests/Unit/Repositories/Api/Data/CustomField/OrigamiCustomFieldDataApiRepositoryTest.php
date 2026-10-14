<?php

namespace Tests\Unit\Repositories\Api\Data\CustomField;

use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldValueListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField\ListCustomFieldRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField\ListCustomFieldValueRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\CustomField\OrigamiCustomFieldDataApiRepository;
use Tests\TestCase;

class OrigamiCustomFieldDataApiRepositoryTest extends TestCase
{
    /**
     * `GET custom_fields` should send the filters, pagination and search params as query, and read the paginated
     * list answered.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCustomFieldApiData(['key' => 'delivery_time']),
        ])));
        $paramBag = new ListCustomFieldRequestParamBag;
        $paramBag->withFilters(['model_type' => 'seller']);
        $paramBag->search('delivery');

        $customFields = (new OrigamiCustomFieldDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'custom_fields');
        $this->assertLastApiRequestQueryWas([
            'filter'        => ['model_type' => 'seller'],
            'page'          => '1',
            'item_per_page' => '20',
            'search'        => 'delivery',
        ]);
        $this->assertInstanceOf(CustomFieldListDto::class, $customFields);
        $this->assertSame(['delivery_time'], $customFields->data->pluck('key')->all());
    }

    /**
     * `GET custom_fields/{id}/values` should send the search param as query, and read the list of values answered.
     */
    public function test_get_values_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockCustomFieldValueApiData(['value' => 'express']),
            $this->mockCustomFieldValueApiData(['value' => 'standard']),
        ]]));
        $paramBag = new ListCustomFieldValueRequestParamBag;
        $paramBag->search('s');

        $values = (new OrigamiCustomFieldDataApiRepository($restClient))->getValues(12, $paramBag);

        $this->assertLastApiRequestWas('GET', 'custom_fields/12/values');
        $this->assertLastApiRequestQueryWas(['search' => 's']);
        $this->assertInstanceOf(CustomFieldValueListDto::class, $values);
        $this->assertSame(['express', 'standard'], $values->data->pluck('value')->all());
    }
}
