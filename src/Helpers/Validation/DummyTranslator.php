<?php

namespace OrigamiMp\OrigamiApiSdk\Helpers\Validation;

use Illuminate\Contracts\Translation\Translator;

/**
 * This Dummy translator is only used to construct Laravel validator.
 */
class DummyTranslator implements Translator
{
    // TODO DEV : Add real translation to validator
    public function get($key, array $replace = [], $locale = null)
    {
        return $key;
    }

    public function choice($key, $number, array $replace = [], $locale = null)
    {
        return $key;
    }

    public function getLocale()
    {
        return 'fr';
    }

    public function setLocale($locale)
    {
        //
    }
}
