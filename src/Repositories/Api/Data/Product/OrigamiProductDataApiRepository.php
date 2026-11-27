<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Product;

use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Product\ListProductRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiProductDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ProductListDtoNotConstructableException
     */
    public function list(?ListProductRequestParamBag $paramBag = null): ProductListDto
    {
        $response = $this->restClient->get('catalog/products', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ProductListDto($responseContent);
    }
}
