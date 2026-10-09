<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTypesDtoNotConstructableException;

class FormTypesDto extends ApiResponseDto implements Arrayable
{
    /** @var Collection<int, FormTypeDto> */
    public Collection $types;

    /** @var string[] */
    public array $fileExtensions;

    public int $fileMaxSizeKb;

    public int $fileMaxFiles;

    /** @var array<string, int> */
    public array $maxLength;

    /** @var array<string, int> */
    public array $limits;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'types'            => fn (array $types) => $this->types = collect($types)->map(fn (object $type) => new FormTypeDto($type)),
            'file.extensions'  => 'fileExtensions',
            'file.max_size_kb' => 'fileMaxSizeKb',
            'file.max_files'   => 'fileMaxFiles',
            'max_length'       => fn (object $maxLength) => $this->maxLength = (array) $maxLength,
            'limits'           => fn (object $limits) => $this->limits = (array) $limits,
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'types'            => ['present', 'array'],
            'file.extensions'  => ['required', 'array'],
            'file.max_size_kb' => ['required', 'integer'],
            'file.max_files'   => ['required', 'integer'],
            'max_length'       => ['required', 'array'],
            'limits'           => ['required', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormTypesDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
