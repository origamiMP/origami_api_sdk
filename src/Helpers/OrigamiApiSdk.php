<?php

namespace OrigamiMp\OrigamiApiSdk\Helpers;

class OrigamiApiSdk
{
    /**
     * Get Origami Api Sdk version number from composer.json
     */
    public static function getVersion(): string
    {
        $projectBasePath = dirname(__FILE__, 3);

        $pathToComposerJson = "$projectBasePath/composer.json";
        $encodedComposerJsonContent = file_get_contents($pathToComposerJson);

        $decodedComposerJsonContent = json_decode($encodedComposerJsonContent, true);

        return $decodedComposerJsonContent['version'];
    }
}
