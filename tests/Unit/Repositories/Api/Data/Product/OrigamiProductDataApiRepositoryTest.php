<?php

namespace Tests\Unit\Repositories\Api\Data\Product;

use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductTranslationDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Product\ListProductRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Product\OrigamiProductDataApiRepository;
use Tests\TestCase;

class OrigamiProductDataApiRepositoryTest extends TestCase
{
    /**
     * `GET catalog/products` should send the ids as a list, without pagination, and read the products.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockProductApiData(['id' => 1]),
            $this->mockProductApiData(['id' => 2]),
        ])));
        $paramBag = new ListProductRequestParamBag;
        $paramBag->setFilters(['id' => [1, 2], 'unknown' => 3]);
        $paramBag->setWithoutPagination(true);

        $products = (new OrigamiProductDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'catalog/products');
        $this->assertLastApiRequestQueryWas([
            'filter'             => ['id' => ['1', '2']],
            'page'               => '1',
            'item_per_page'      => '20',
            'without_pagination' => '1',
        ]);
        $this->assertInstanceOf(ProductListDto::class, $products);
        $this->assertSame([1, 2], $products->data->pluck('id')->all());
    }

    /**
     * `GET catalog/products` should only request the available includes, and read the translations.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockProductApiData(['id' => 42, 'translations' => ['data' => [$this->mockProductTranslationApiData(['locale' => 'fr', 'name' => 'Coupe femme'])]]]),
        ])));
        $paramBag = new ListProductRequestParamBag;
        $paramBag->setIncludes(['translations', 'variants', 'unknown']);

        $products = (new OrigamiProductDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'page'          => '1',
            'item_per_page' => '20',
            'include'       => ['translations'],
        ]);
        $product = $products->data->first();
        $this->assertContainsOnlyInstancesOf(ProductTranslationDto::class, $product->translations);
        $this->assertSame('Coupe femme', $product->translations->first()->name);
    }
}
