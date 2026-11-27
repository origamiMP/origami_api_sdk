<?php

namespace Tests\Unit\Repositories\Api\Data\Shipping;

use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Shipping\ShippingZoneListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Shipping\ShippingZoneTranslationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Shipping\ListShippingZoneRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Shipping\OrigamiShippingZoneDataApiRepository;
use Tests\TestCase;

class OrigamiShippingZoneDataApiRepositoryTest extends TestCase
{
    /**
     * `GET shipping/zones` should send the search, the pagination and the filters, and read each zone of the page.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockShippingZoneApiData(['id' => 1]),
            $this->mockShippingZoneApiData(['id' => 2, 'postcodes' => '20000-20999']),
        ])));
        $paramBag = new ListShippingZoneRequestParamBag;
        $paramBag->setSearch('corse');
        $paramBag->setPage(2);
        $paramBag->setFilters(['id' => [1, 2]]);

        $shippingZones = (new OrigamiShippingZoneDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'shipping/zones');
        $this->assertLastApiRequestQueryWas([
            'filter'        => ['id' => ['1', '2']],
            'page'          => '2',
            'item_per_page' => '20',
            'search'        => 'corse',
        ]);
        $this->assertInstanceOf(ShippingZoneListDto::class, $shippingZones);
        $this->assertSame([1, 2], $shippingZones->data->pluck('id')->all());
        $this->assertSame([null, '20000-20999'], $shippingZones->data->pluck('postcodes')->all());
        $this->assertSame('2024-05-12 16:12:56', $shippingZones->data->first()->createdAt->format('Y-m-d H:i:s'));
    }

    /**
     * A filter not available on `GET shipping/zones` should not be sent.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([])));
        $paramBag = new ListShippingZoneRequestParamBag;
        $paramBag->setFilters(['id' => 42, 'name' => 'Europe']);

        (new OrigamiShippingZoneDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'        => ['id' => '42'],
            'page'          => '1',
            'item_per_page' => '20',
        ]);
    }

    /**
     * `GET shipping/zones` should only request the available includes, and read the translations and the countries.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockShippingZoneApiData([
                'translations' => ['data' => [
                    $this->mockShippingZoneTranslationApiData(['locale' => 'en', 'name' => 'Europe']),
                    $this->mockShippingZoneTranslationApiData(['locale' => 'fr', 'name' => 'Europe', 'default' => false]),
                ]],
                'countries' => ['data' => [$this->mockCountryApiData(['id' => 42])]],
            ]),
        ])));
        $paramBag = new ListShippingZoneRequestParamBag;
        $paramBag->setIncludes(['translations', 'countries', 'unknown']);

        $shippingZones = (new OrigamiShippingZoneDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['translations', 'countries'],
        ]);
        $shippingZone = $shippingZones->data->first();
        $this->assertContainsOnlyInstancesOf(ShippingZoneTranslationDto::class, $shippingZone->translations);
        $this->assertSame(['en', 'fr'], $shippingZone->translations->pluck('locale')->all());
        $this->assertSame([true, false], $shippingZone->translations->pluck('default')->all());
        $this->assertContainsOnlyInstancesOf(CountryDto::class, $shippingZone->countries);
        $this->assertSame([42], $shippingZone->countries->pluck('id')->all());
    }

    /**
     * `GET shipping/zones` should be callable without param bag, then sending no query.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockShippingZoneApiData(['id' => 42]),
        ])));

        $shippingZones = (new OrigamiShippingZoneDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'shipping/zones');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $shippingZones->data->first()->id);
        $this->assertFalse(isset($shippingZones->data->first()->translations));
    }
}
