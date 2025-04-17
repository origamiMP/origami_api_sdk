<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class ParamBag
{
    protected function asEncodableArray(?array $propertiesList = null): array
    {
        $allProperties = get_object_vars($this);
        $properties = is_null($propertiesList) ? $allProperties : Arr::only($allProperties, $propertiesList);

        $encodableArray = [];

        foreach ($properties as $propertyName => $propertyValue) {
            if ($this->doesPropertyHaveCustomCastMethod($propertyName)) {
                $encodableArray[$propertyName] = $this->useCustomCastForProperty($propertyName, $propertyValue);
            } else {
                $encodableArray[$propertyName] = $this->castPropertyToEncodableType($propertyValue);
            }
        }

        return $encodableArray;
    }

    protected function castPropertyToEncodableType(mixed $propertyValue): int|string|array
    {
        if ($propertyValue instanceof ParamBag) {
            return $propertyValue->asEncodableArray();
        }

        if (is_array($propertyValue)) {
            return $this->castArrayToEncodableType($propertyValue);
        }

        if ($propertyValue instanceof \BackedEnum) {
            return $this->castBackedEnumToEncodableType($propertyValue);
        }

        if ($propertyValue instanceof Carbon) {
            return $this->castCarbonToEncodableType($propertyValue);
        }

        return $propertyValue;
    }

    protected function castArrayToEncodableType(array $array): array
    {
        $encodableArray = [];

        foreach ($array as $index => $value) {
            $encodableArray[$index] = $this->castPropertyToEncodableType($value);
        }

        return $encodableArray;
    }

    protected function castBackedEnumToEncodableType(\BackedEnum $enum): string|int
    {
        return $enum->value;
    }

    protected function castCarbonToEncodableType(Carbon $dateTime): string|int
    {
        return $dateTime->format('Y-m-d');
    }

    protected function getCustomCastMethodNameForProperty(string $propertyName): string
    {
        $formattedPropertyName = Str::ucfirst(Str::camel($propertyName));

        return "cast{$formattedPropertyName}ToEncodableType";
    }

    protected function doesPropertyHaveCustomCastMethod(string $propertyName): bool
    {
        return method_exists($this, $this->getCustomCastMethodNameForProperty($propertyName));
    }

    protected function useCustomCastForProperty(string $propertyName, mixed $propertyValue): mixed
    {
        $methodName = $this->getCustomCastMethodNameForProperty($propertyName);

        return $this->$methodName($propertyValue);
    }
}
