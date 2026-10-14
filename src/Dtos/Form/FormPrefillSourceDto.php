<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPrefillSourceDtoNotConstructableException;

class FormPrefillSourceDto extends ApiResponseDto implements Arrayable
{
    public string $source;

    public string $label;

    public ?string $key = null;

    public ?string $type = null;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'source' => 'source',
            'label'  => 'label',
            'key'    => 'key',
            'type'   => 'type',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'source' => ['required', 'string'],
            'label'  => ['present', 'string'],
            'key'    => ['nullable', 'string'],
            'type'   => ['nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormPrefillSourceDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
