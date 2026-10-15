<?php

namespace Tests\Unit\Repositories\Api\Guest\Country;

use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Country\ListCountriesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Country\OrigamiCountryGuestApiRepository;
use Tests\TestCase;

class OrigamiCountryGuestApiRepositoryTest extends TestCase
{
    /**
     * `GET countries` should send the pagination and search params as query, and read the paginated list answered.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockCountryApiData(['iso_2' => 'FR']),
            $this->mockCountryApiData(['iso_2' => 'BE']),
        ])));
        $paramBag = new ListCountriesRequestParamBag;
        $paramBag->page(2);
        $paramBag->perPage(50);
        $paramBag->search('fra');

        $countries = (new OrigamiCountryGuestApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'countries');
        $this->assertLastApiRequestQueryWas(['page' => '2', 'item_per_page' => '50', 'search' => 'fra']);
        $this->assertInstanceOf(CountryListDto::class, $countries);
        $this->assertSame(['FR', 'BE'], $countries->data->pluck('iso2')->all());
        $this->assertSame(2, $countries->total);
    }

    /**
     * `GET countries` should be callable without param bag, then sending no query.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockCountryApiData(['id' => 42])])));

        $countries = (new OrigamiCountryGuestApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'countries');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertInstanceOf(CountryListDto::class, $countries);
        $this->assertSame(42, $countries->data->first()->id);
    }
}
