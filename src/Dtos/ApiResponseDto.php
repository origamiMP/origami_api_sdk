<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OrigamiMp\OrigamiApiSdk\Enums\Json\JsonResponseErrorEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;

abstract class ApiResponseDto implements Arrayable
{
    protected object $apiResponse;

    public function __construct(object $apiResponse)
    {
        $this->apiResponse = $apiResponse;

        $this->fill();
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

    abstract protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException;

    /**
     * Keys of the data structure the API does not always send, in dotted notation (`data.field`). Any other key
     * missing from the response makes the Dto not constructable. Includes are always optional.
     *
     * @return string[]
     */
    protected function getOptionalKeys(): array
    {
        return [];
    }

    /**
     * Keys of the data structure holding an include, always optional. A Dto with includes gets them from `HasIncludes`.
     *
     * @return string[]
     */
    protected function getIncludeKeys(): array
    {
        return [];
    }

    protected function getDataToFillFrom(): object
    {
        return $this->apiResponse;
    }

    /**
     * Fills every property of the data structure (`getDefaultDataStructureToProperties()`) from the API response.
     *
     * @throws ApiResponseDtoNotConstructableException
     */
    protected function fill(): void
    {
        $propertiesIndexedByDottedPaths = Arr::dot($this->getDefaultDataStructureToProperties());

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
            $this->getDataToFillFrom(),
            $dottedPath,
            JsonResponseErrorEnum::MISSING_PROPERTY,
        );

        if ($dataToHave === JsonResponseErrorEnum::MISSING_PROPERTY) {
            if ($this->isKeyOptional($dottedPath)) {
                return;
            }

            $classShortName = $this->getClassShortName();

            throw $this->getDefaultNotConstructableException(
                "[{$classShortName}] Missing key '{$dottedPath}' in the API response",
            );
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
                "[{$classShortName}] Could not assign data '{$dottedPath}' to property: {$e->getMessage()}",
                $e,
            );
        }
    }

    protected function isKeyOptional(string $dottedPath): bool
    {
        $include = Str::before($dottedPath, '.');

        if (in_array($include, $this->getIncludeKeys(), true)) {
            return true;
        }

        return in_array($dottedPath, $this->getOptionalKeys(), true);
    }

    /**
     * @throws ApiResponseDtoNotConstructableException
     */
    protected function throwIfDataFieldOnObjectIsEmpty(object $object): void
    {
        if (! isset($object->data)) {
            $classShortName = $this->getClassShortName();

            throw $this->getDefaultNotConstructableException(
                "[{$classShortName}] Object part from API response does not have mandatory `data` field.",
            );
        }
    }

    protected function getClassShortName(): string
    {
        return Str::afterLast(get_class($this), '\\');
    }

    /**
     * Builds an array from this DTO's public, initialized properties, for `toArray()`. Keys
     * default to the property's own (camelCase) name, unchanged; pass $keyOverrides for the
     * rare property that should be exposed under a different key.
     *
     * @param  array<string, string>  $keyOverrides  propertyName => arrayKey
     */
    protected function arrayFromPublicProperties(array $keyOverrides = []): array
    {
        $array = [];

        foreach ((new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (! $property->isInitialized($this)) {
                continue;
            }

            $name = $property->getName();
            $key = $keyOverrides[$name] ?? $name;

            $array[$key] = $this->serializeValueForArray($property->getValue($this));
        }

        return $array;
    }

    protected function serializeValueForArray(mixed $value): mixed
    {
        return match (true) {
            $value instanceof \BackedEnum        => $value->value,
            $value instanceof \DateTimeInterface => $value->format(DATE_ATOM),
            $value instanceof Collection         => $value->map(fn ($item) => $this->serializeValueForArray($item))->all(),
            $value instanceof Arrayable          => $value->toArray(),
            default                              => $value,
        };
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
