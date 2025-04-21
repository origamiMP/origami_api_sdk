<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos;

use Illuminate\Support\Arr;
use OrigamiMp\OrigamiApiSdk\Enums\Json\JsonResponseErrorEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;

abstract class ApiResponseDto
{
    protected object $apiResponse;

    public function __construct(object $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function getApiResponse(): object
    {
        return $this->apiResponse;
    }

    abstract public static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException;

    /**
     * This method defines the mapping between the expected data structure from an
     * API response and the corresponding class properties these data fields
     * should be assigned to.
     * Example :
     * [
     *     'account' => [
     *         'id'        => 'id',
     *         'balance'   => 'balance',
     *         'status'    => 'status',
     *         'isblocked' => 'isBlocked',
     *     ]
     * ]
     *
     * This would mean that we expect to find in the API response a key 'account'
     * containing an object with a key 'id', and that the value at this key should be
     * assigned to the property $id of the class inheriting this one.
     */
    abstract public function getDefaultDataStructureToProperties(): array;

    /**
     * This method is used to check if an API response has the fields
     * expected by its corresponding dto (that inherits this class).
     *
     * Also, if every expected field is present, its value will be assigned to
     * the corresponding property. This correspondance is defined by the parameter
     * $dataStructureToProperties. If it is empty, we will use the one defined in
     * the method getDefaultDataStructureToProperties().
     *
     * This method should typically be called in the dto constructor.
     *
     *
     * @throws ApiResponseDtoNotConstructableException
     */
    public function throwIfDataIsMissingFromApiResponse(
        array $dataStructureToProperties = []
    ): void {
        if (empty($dataStructureToProperties)) {
            $dataStructureToProperties = $this->getDefaultDataStructureToProperties();
        }

        $propertiesIndexedByDottedPaths = Arr::dot($dataStructureToProperties);

        foreach ($propertiesIndexedByDottedPaths as $dottedPath => $propertyCaster) {
            $this->fillPropertyWithData($dottedPath, $propertyCaster);
        }
    }

    /**
     * @throws ApiResponseDtoNotConstructableException
     */
    protected function fillPropertyWithData(string $dottedPath, mixed $propertyCaster): void
    {
        $dataToHave = data_get(
            $this->apiResponse,
            $dottedPath,
            JsonResponseErrorEnum::MISSING_PROPERTY,
        );

        if ($dataToHave === JsonResponseErrorEnum::MISSING_PROPERTY) {
            throw (
                $this->getDefaultNotConstructableException(
                    "No error sent back by API but '$dottedPath' is missing.",
                )
            );
        }

        try {
            if (is_string($propertyCaster)) {
                $this->$propertyCaster = $dataToHave;
            } elseif (is_callable($propertyCaster)) {
                $propertyCaster($dataToHave);
            }
        } catch (\Throwable $e) {
            throw (
                $this->getDefaultNotConstructableException(
                    "Could not assign data '$dottedPath' to property : {$e->getMessage()}",
                    $e,
                )
            );
        }
    }
}
