<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\RequiredIf;

abstract class ParamBag
{
    protected function asEncodableArray(?array $propertiesList = null): array
    {
        $allProperties = $this->getAllObjectVars();

        $properties = is_null($propertiesList) ? $allProperties : Arr::only($allProperties, $propertiesList);
        $propertiesAsSnakeCase = $this->propertyNamesToSnakeCase($properties);

        $encodableArray = [];

        foreach ($propertiesAsSnakeCase as $propertyName => $propertyValue) {
            if ($this->doesPropertyHaveCustomCastMethod($propertyName)) {
                $encodableArray[$propertyName] = $this->useCustomCastForProperty($propertyName, $propertyValue);
            } else {
                $encodableArray[$propertyName] = $this->castPropertyToEncodableType($propertyValue);
            }
        }

        return $encodableArray;
    }

    /**
     * get_object_vars() only returns properties visible from the calling scope. Called directly here,
     * it would miss private properties declared by traits used on subclasses (ex: HasIncludes::$include),
     * since those are private to the subclass, not to this parent class. Binding the call to the object's
     * own scope at runtime restores visibility of those properties.
     */
    private function getAllObjectVars(): array
    {
        return Closure::bind(fn () => get_object_vars($this), $this, $this)();
    }

    protected function castPropertyToEncodableType(mixed $propertyValue): bool|int|float|string|array
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
        return $dateTime->format('Y-m-d H:i:s');
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

    protected function propertyNamesToSnakeCase(array $propertyList): array
    {
        $newPropertyList = [];

        foreach ($propertyList as $propertyName => $propertyValue) {
            $newPropertyList[Str::snake($propertyName)] = $propertyValue;
        }

        return $newPropertyList;
    }

    /**
     * $rules should be an array of rules that are applied to an attribute. If $condition is true,
     * any 'required' rule (any rule that contains the word 'required') in $rules will be removed.
     */
    protected function removeRequiredRuleIf(array $rules, bool $condition): array
    {
        if (! $condition) {
            return $rules;
        }

        return array_filter($rules, fn ($rule) => ! $rule instanceof RequiredIf && (! is_string($rule) || ! Str::contains($rule, 'required')));
    }
}
