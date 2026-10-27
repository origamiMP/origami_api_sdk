<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\RequiredIf;

abstract class ParamBag
{
    /**
     * The properties this param bag sends, with their API key: `propertyName => 'api_key'`. A property needing a
     * custom cast declares a closure instead, receiving the property value and returning a single API key / value
     * pair, sent as is: `propertyName => fn (Carbon $date) => ['api_key' => $date->format('Y-m-d')]`.
     * A property not listed, or not set, is not sent.
     *
     * @return array<string, string|Closure(mixed): array<string, mixed>>
     */
    abstract protected function getPropertiesToApiKeys(): array;

    /**
     * @param  array<string, string|Closure(mixed): array<string, mixed>>  $propertiesToApiKeys  property name => API key, or closure
     */
    protected function asEncodableArray(array $propertiesToApiKeys): array
    {
        $properties = $this->getAllObjectVars();

        $encodableArray = [];

        foreach ($propertiesToApiKeys as $propertyName => $apiKeyOrCaster) {
            if (! array_key_exists($propertyName, $properties)) {
                continue;
            }

            if ($apiKeyOrCaster instanceof Closure) {
                $encodableArray = [
                    ...$encodableArray,
                    ...$this->useCustomCastForProperty($propertyName, $apiKeyOrCaster, $properties[$propertyName]),
                ];
            } else {
                $encodableArray[$apiKeyOrCaster] = $this->castPropertyToEncodableType($properties[$propertyName]);
            }
        }

        return $encodableArray;
    }

    /**
     * @return array<string, mixed>
     */
    private function useCustomCastForProperty(string $propertyName, Closure $caster, mixed $propertyValue): array
    {
        $apiKeyToValue = $caster($propertyValue);

        if (! is_array($apiKeyToValue) || count($apiKeyToValue) !== 1 || ! is_string(array_key_first($apiKeyToValue))) {
            $classShortName = class_basename($this);

            throw new \LogicException("[$classShortName] The cast of '$propertyName' must return a single API key / value pair.");
        }

        return $apiKeyToValue;
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

    protected function castPropertyToEncodableType(mixed $propertyValue): null|bool|int|float|string|array
    {
        if ($propertyValue instanceof ParamBag) {
            return $propertyValue->asEncodableArray($propertyValue->getPropertiesToApiKeys());
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
