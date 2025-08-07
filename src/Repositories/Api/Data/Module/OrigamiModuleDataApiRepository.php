<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\ListModuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiModuleDataApiRepository extends OrigamiDataApiRepository
{
    public function list(ListModuleRequestParamBag $paramBag): ModuleListDto
    {
        $response = $this->restClient->get('modules', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new ModuleListDto($responseContent);
    }
}
