<?php

namespace OrigamiMp\OrigamiApiSdk\Helpers\Validation;

use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
    public function __construct(
        array $data,
        array $rules,
        array $messages = [],
        array $attributes = []
    ) {
        parent::__construct(new DummyTranslator(), $data, $rules, $messages, $attributes);
    }
}
