<?php

if (! function_exists('objectToArray')) {
    /**
     * Convert an object to an array
     *
     * @param  object  $object  The object to convert
     * @return array The converted array
     */
    function objectToArray(object $object): array
    {
        return json_decode(json_encode($object), true);
    }
}

if (! function_exists('getOrigamiApiSdkVersion')) {
    /**
     * Get Origami Api Sdk version number from composer.json
     */
    function getOrigamiApiSdkVersion(): string
    {
        $projectBasePath = dirname(__FILE__, 2);

        $pathToComposerJson = "$projectBasePath/composer.json";
        $encodedComposerJsonContent = file_get_contents($pathToComposerJson);

        $decodedComposerJsonContent = json_decode($encodedComposerJsonContent, true);

        return $decodedComposerJsonContent['version'];
    }
}

if (! function_exists('camelCaseToSnakeCase')) {
    /**
     * Convert a camelCase string to snake_case
     *
     * @param  string  $input  The camelCase string to convert
     * @return string The converted snake_case string
     */
    function camelCaseToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}

if (! function_exists('snakeCaseToCamelCase')) {
    /**
     * Convert a snake_case string to camelCase
     *
     * @param  string  $input  The snake_case string to convert
     * @return string The converted camelCase string
     */
    function snakeCaseToCamelCase(string $input): string
    {
        return str_replace('_', '', ucwords($input, '_'));
    }
}
