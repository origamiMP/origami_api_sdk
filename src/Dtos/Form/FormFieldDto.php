<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class FormFieldDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public string $key;

    public FormFieldDtoTypeEnum $type;

    public bool $isRequired;

    public int $position;

    public FormFieldParamsDto $params;

    public bool $visibleToSeller;

    public ?string $prefillSource;

    public bool $prefillReadonly;

    public bool $saveToCustomer;

    public ?FormConditionDto $condition;

    /** @var Collection<int, FormFieldTranslationDto> */
    public Collection $translations;

    /** @var Collection<int, FormFieldOptionDto> */
    public Collection $options;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => FormFieldTranslationDto::class,
            'options'      => FormFieldOptionDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                => 'id',
            'key'               => 'key',
            'type'              => fn (string $type) => $this->type = FormFieldDtoTypeEnum::from($type),
            'is_required'       => 'isRequired',
            'position'          => 'position',
            'params'            => fn (object $params) => $this->params = new FormFieldParamsDto($params),
            'visible_to_seller' => 'visibleToSeller',
            'prefill_source'    => 'prefillSource',
            'prefill_readonly'  => 'prefillReadonly',
            'save_to_customer'  => 'saveToCustomer',
            'condition'         => fn (?object $condition) => $this->condition = $condition ? new FormConditionDto($condition) : null,
            'translations'      => fn (object $translations) => $this->translations = $this->collectIncluded($translations, FormFieldTranslationDto::class),
            'options'           => fn (object $options) => $this->options = $this->collectIncluded($options, FormFieldOptionDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormFieldDtoNotConstructableException($msg, previous: $previous);
    }
}
