<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Tax;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Tax\TaxDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class TaxDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public float $value;

    public TaxDtoTypeEnum $type;

    public ?int $idScsTax;

    public bool $isDefault;

    public ?int $moduleId;

    /** @var Collection<int, TaxTranslationDto> */
    public Collection $translations;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => TaxTranslationDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'value'        => fn ($value) => $this->value = (float) $value,
            'type'         => fn ($type) => $this->type = TaxDtoTypeEnum::from($type),
            'id_scs_tax'   => 'idScsTax',
            'is_default'   => 'isDefault',
            'module_id'    => 'moduleId',
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, TaxTranslationDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'           => ['required', 'integer'],
            'value'        => ['required', 'numeric'],
            'type'         => ['required', Rule::enum(TaxDtoTypeEnum::class)],
            'id_scs_tax'   => ['present', 'nullable', 'integer'],
            'is_default'   => ['required', 'boolean'],
            'module_id'    => ['present', 'nullable', 'integer'],
            'translations' => ['required', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new TaxDtoNotConstructableException($msg, previous: $previous);
    }
}
