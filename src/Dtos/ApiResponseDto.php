<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use OrigamiMp\OrigamiApiSdk\Enums\Json\JsonResponseErrorEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use OrigamiMp\OrigamiApiSdk\Helpers\Validation\Validator;

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
    abstract protected function getDefaultDataStructureToProperties(): array;

    /**
     * This method defines the rules (based on Laravel validation system) that should be applied to
     * validate the original api response before filling properties.
     *
     * The returned array should be formatted to be compatible with Laravel Validator, like so for example :
     * [
     *     'account.id'      => ['required', 'string'],
     *     'account.balance' => ['required', 'int'],
     * ]
     */
    abstract protected function validationRulesForProperties(): array;

    abstract protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException;

    protected function getDataToValidateAndFillFrom(): object
    {
        return $this->apiResponse;
    }

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
    protected function validateAndFill(
        array $dataStructureToProperties = []
    ): void {
        if (empty($dataStructureToProperties)) {
            $dataStructureToProperties = $this->getDefaultDataStructureToProperties();
        }

        try {
            $this->validateApiResponseRawValues();
        } catch (ValidationException $e) {
            $summary = self::getSummaryFromValidationException($e);
            $classShortName = $this->getClassShortName();

            throw $this->getDefaultNotConstructableException(
                "[$classShortName] Api response data failed to be validated : {$summary}",
                $e,
            );
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
        $dataToHave = Obj::get(
            $this->getDataToValidateAndFillFrom(),
            $dottedPath,
            JsonResponseErrorEnum::MISSING_PROPERTY,
        );

        if ($dataToHave === JsonResponseErrorEnum::MISSING_PROPERTY) {
            return;
        }

        try {
            if (is_string($propertyCaster)) {
                $this->$propertyCaster = $dataToHave;
            } elseif (is_callable($propertyCaster)) {
                $propertyCaster($dataToHave);
            }
        } catch (\Throwable $e) {
            $classShortName = $this->getClassShortName();

            throw $this->getDefaultNotConstructableException(
                "[$classShortName] Could not assign data '$dottedPath' to property : {$e->getMessage()}",
                $e,
            );
        }
    }

    /**
     * @throws ValidationException
     */
    protected function validateApiResponseRawValues(): void
    {
        $responseRawValuesIndexedByDottedPaths = objectToArray($this->getDataToValidateAndFillFrom());
        $rules = $this->validationRulesForProperties();

        $validator = new Validator($responseRawValuesIndexedByDottedPaths, $rules);

        $validator->validate();
    }

    /**
     * @throws ApiResponseDtoNotConstructableException
     */
    protected function throwIfDataFieldOnObjectIsEmpty(object $object): void
    {
        if (! isset($object->data)) {
            $classShortName = $this->getClassShortName();

            throw $this->getDefaultNotConstructableException(
                "[$classShortName] Object part from API response does not have mandatory `data` field.",
            );
        }
    }

    protected function getClassShortName(): string
    {
        return Str::afterLast(get_class($this), '\\');
    }

    protected static function getSummaryFromValidationException(ValidationException $e): string
    {
        $summary = [];

        foreach ($e->errors() as $field => $fieldErrors) {
            $fieldErrorsSummary = implode(', ', $fieldErrors);
            $summary[] = "$field ($fieldErrorsSummary)";
        }

        return implode(', ', $summary);
    }
}
