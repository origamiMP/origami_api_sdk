<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Taxes;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Taxes\TaxTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Taxes\TaxDtoNotConstructableException;

class TaxDto extends ApiResponseDto
{
    public int $id;

    public float $value;

    public TaxTypeEnum $type;

    public ?int $idScsTax;

    public bool $isDefault;

    public ?int $moduleId;

    /**
     * @var Collection|TaxTranslationDto[]
     */
    public Collection $translations;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'value'        => fn ($value) => $this->value = (float) $value,
            'type'         => fn ($type) => $this->type = TaxTypeEnum::from($type),
            'id_scs_tax'   => 'idScsTax',
            'is_default'   => 'isDefault',
            'module_id'    => 'moduleId',
            'translations' => fn ($translations) => $this->initTranslations($translations),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $types = collect(TaxTypeEnum::cases())->pluck('value');

        return [
            'id'           => ['required', 'integer'],
            'value'        => ['required', 'numeric'],
            'type'         => ['required', Rule::in($types)],
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

    protected function initTranslations(object $translations): void
    {
        $this->throwIfDataFieldOnObjectIsEmpty($translations);

        $this->translations = collect($translations->data)->map(fn ($translation) => new TaxTranslationDto($translation));
    }
}
