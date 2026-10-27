<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class UpdateUserRequestParamBag extends RequestParamBag
{
    /**
     * JSON-encoded configuration used by Origami Back-office. The Origami API replaces it as a whole,
     * so the existing keys must be merged into it before sending.
     */
    public string $externalConfiguration;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'externalConfiguration' => 'external_configuration',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'external_configuration' => ['json'],
        ];
    }
}
