<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

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

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormConditionDtoNotConstructableException($msg, previous: $previous);
    }
}
