<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\CustomField;

use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldValueListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldValueListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField\ListCustomFieldRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\CustomField\ListCustomFieldValueRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiCustomFieldDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws CustomFieldListDtoNotConstructableException
     */
    public function list(?ListCustomFieldRequestParamBag $paramBag = null): CustomFieldListDto
    {
        $response = $this->restClient->get('custom_fields', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new CustomFieldListDto($responseContent);
    }

    /**
     * Get the possible values of a `select`/`multiple` custom field
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws CustomFieldValueListDtoNotConstructableException
     */
    public function getValues(int $id, ?ListCustomFieldValueRequestParamBag $paramBag = null): CustomFieldValueListDto
    {
        $response = $this->restClient->get("custom_fields/{$id}/values", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new CustomFieldValueListDto($responseContent);
    }
}
