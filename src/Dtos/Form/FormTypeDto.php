<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTypeDtoNotConstructableException;

class FormTypeDto extends ApiResponseDto implements Arrayable
{
    public string $type;

    public string $label;

    public bool $hasOptions;

    public bool $acceptsSeveralOptions;

    public bool $canBePrefilled;

    /** @var string[] */
    public array $params;

    /** @var string[] */
    public array $conditionOperators;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'type'                    => 'type',
            'label'                   => 'label',
            'has_options'             => 'hasOptions',
            'accepts_several_options' => 'acceptsSeveralOptions',
            'can_be_prefilled'        => 'canBePrefilled',
            'params'                  => 'params',
            'condition_operators'     => 'conditionOperators',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'type'                    => ['required', 'string'],
            'label'                   => ['present', 'string'],
            'has_options'             => ['required', 'boolean'],
            'accepts_several_options' => ['required', 'boolean'],
            'can_be_prefilled'        => ['required', 'boolean'],
            'params'                  => ['present', 'array'],
            'condition_operators'     => ['present', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormTypeDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
