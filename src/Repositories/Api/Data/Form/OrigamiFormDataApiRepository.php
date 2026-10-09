<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Form;

use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormPrefillSourcesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTypesDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiClientErrorException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiMultipleException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPrefillSourcesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTypesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\CreateFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\ListFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\UpdateFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiFormDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FormListDtoNotConstructableException
     */
    public function list(ListFormRequestParamBag $paramBag): FormListDto
    {
        $response = $this->restClient->get('forms', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new FormListDto($responseContent);
    }

    /**
     * Get a form with its whole tree (pages, sections, fields, options)
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FormDtoNotConstructableException
     */
    public function get(int $id): FormDto
    {
        $response = $this->restClient->get("forms/{$id}");
        $responseContent = json_decode($response->getBody()->getContents());

        return new FormDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FormDtoNotConstructableException
     */
    public function create(CreateFormRequestParamBag $paramBag): FormDto
    {
        $response = $this->restClient->post('forms', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new FormDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Answers 204 without a body: fetch the form again to get the ids of new nodes.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiClientErrorException
     * @throws OrigamiApiMultipleException
     */
    public function update(int $id, UpdateFormRequestParamBag $paramBag): void
    {
        $this->restClient->patch("forms/{$id}", $paramBag);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiClientErrorException
     */
    public function delete(int $id): void
    {
        $this->restClient->delete("forms/{$id}");
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FormDtoNotConstructableException
     */
    public function duplicate(int $id): FormDto
    {
        $response = $this->restClient->post("forms/{$id}/duplicate");
        $responseContent = json_decode($response->getBody()->getContents());

        return new FormDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FormTypesDtoNotConstructableException
     */
    public function getTypes(): FormTypesDto
    {
        $response = $this->restClient->get('forms/types');
        $responseContent = json_decode($response->getBody()->getContents());

        return new FormTypesDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws FormPrefillSourcesDtoNotConstructableException
     */
    public function getPrefillSources(): FormPrefillSourcesDto
    {
        $response = $this->restClient->get('forms/prefill-sources');
        $responseContent = json_decode($response->getBody()->getContents());

        return new FormPrefillSourcesDto($responseContent);
    }
}
