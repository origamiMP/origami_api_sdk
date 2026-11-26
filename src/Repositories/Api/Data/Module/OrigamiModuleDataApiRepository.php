<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetModuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\ListModuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\UpdateModuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiModuleDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ModuleListDtoNotConstructableException
     */
    public function list(?ListModuleRequestParamBag $paramBag = null): ModuleListDto
    {
        $response = $this->restClient->get('modules', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ModuleListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ModuleDtoNotConstructableException
     */
    public function get(int $id, ?GetModuleRequestParamBag $paramBag = null): ModuleDto
    {
        $response = $this->restClient->get("modules/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ModuleDto($this->getResponseContentData($responseContent));
    }

    /**
     * The configurations sent replace the saved ones as a whole (the API only keeps the keys it never exposes).
     * Answers 204 without a body: fetch the module again to get what was saved.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function update(int $id, UpdateModuleRequestParamBag $paramBag): void
    {
        $this->restClient->patch("modules/{$id}", $paramBag);
    }
}
