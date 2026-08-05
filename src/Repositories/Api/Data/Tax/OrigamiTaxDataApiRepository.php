<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Tax;

use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax\CreateTaxRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax\UpdateTaxRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiTaxDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Create a new tax
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws TaxDtoNotConstructableException
     */
    public function create(CreateTaxRequestParamBag $paramBag): TaxDto
    {
        $response = $this->restClient->post('taxes', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new TaxDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Update an existing tax
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function update(int $id, UpdateTaxRequestParamBag $paramBag): void
    {
        $this->restClient->patch("taxes/{$id}", $paramBag);
    }

    /**
     * Delete an existing tax
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function delete(int $id): void
    {
        $this->restClient->delete("taxes/{$id}");
    }
}
