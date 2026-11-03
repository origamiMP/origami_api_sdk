<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Tax;

use Illuminate\Support\Collection;
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

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new TaxDtoNotConstructableException($msg, previous: $previous);
    }
}
