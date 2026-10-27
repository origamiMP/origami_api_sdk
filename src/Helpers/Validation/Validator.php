<?php

namespace OrigamiMp\OrigamiApiSdk\Helpers\Validation;

use Illuminate\Validation\Validator as BaseValidator;

/**
 * Laravel validator usable outside of Laravel, built with a translator returning the keys of the messages.
 */
class Validator extends BaseValidator
{
    public function __construct(
        array $data,
        array $rules,
        array $messages = [],
        array $attributes = []
    ) {
        parent::__construct(new DummyTranslator, $data, $rules, $messages, $attributes);
    }
}
