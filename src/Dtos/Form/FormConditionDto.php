<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormConditionDtoOperatorEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormConditionDtoNotConstructableException;

class FormConditionDto extends ApiResponseDto
{
    public string $fieldKey;

    public FormConditionDtoOperatorEnum $operator;

    public ?string $value;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'field_key' => 'fieldKey',
            'operator'  => fn (string $operator) => $this->operator = FormConditionDtoOperatorEnum::from($operator),
            'value'     => 'value',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'field_key' => ['required', 'string'],
            'operator'  => ['required', Rule::enum(FormConditionDtoOperatorEnum::class)],
            'value'     => ['present', 'nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormConditionDtoNotConstructableException($msg, previous: $previous);
    }
}
