<?php

if (! function_exists('objectToArray')) {
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
