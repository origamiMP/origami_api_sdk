<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\ListModuleRequestParamBag;
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
}
