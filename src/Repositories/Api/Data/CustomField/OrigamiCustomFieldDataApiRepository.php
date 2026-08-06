<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\CustomField;

use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldDefinitionListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldValueListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField\ListCustomFieldRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField\ListCustomFieldValueRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiCustomFieldDataApiRepository extends OrigamiDataApiRepository
{
    public function list(ListCustomFieldRequestParamBag $paramBag): CustomFieldDefinitionListDto
    {
        $response = $this->restClient->get('custom_fields', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new CustomFieldDefinitionListDto($responseContent);
    }

    /**
     * Get the possible values of a `select`/`multiple` custom field
     */
    public function getValues(int $customFieldId, ListCustomFieldValueRequestParamBag $paramBag): CustomFieldValueListDto
    {
        $response = $this->restClient->get("custom_fields/$customFieldId/values", $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new CustomFieldValueListDto($responseContent);
    }
}
