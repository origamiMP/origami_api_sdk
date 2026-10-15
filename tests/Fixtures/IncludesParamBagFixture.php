<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

/**
 * Minimal param bag with includes, to test the HasIncludes trait of param bags: its requests return an
 * IncludesDtoFixture, and it sends only its includes, as query.
 */
class IncludesParamBagFixture extends RequestParamBag
{
    use HasIncludes;

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

    protected function validationRulesForProperties(): array
    {
        return $this->getIncludeValidationRules();
    }

    protected static function getRequestMainDto(): string
    {
        return IncludesDtoFixture::class;
    }
}
