<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionValueDtoNotConstructableException;

/**
 * One answer, with the labels of the form as they were when the customer answered.
 */
class FormSubmissionValueDto extends ApiResponseDto
{
    public int $id;

    public ?int $formFieldId;

    public string $fieldKey;

    public FormFieldDtoTypeEnum $fieldType;

    public ?string $pageTitle;

    public ?string $sectionTitle;

    public string $label;

    public ?string $hint;

    /**
     * Null for a file field: see $files.
     */
    public mixed $value;

    public ?string $displayValue;

    public bool $visibleToSeller;

    public int $position;

    /** @var Collection<int, FormSubmissionFileDto> */
    public Collection $files;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                => 'id',
            'form_field_id'     => 'formFieldId',
            'field_key'         => 'fieldKey',
            'field_type'        => fn (string $fieldType) => $this->fieldType = FormFieldDtoTypeEnum::from($fieldType),
            'page_title'        => 'pageTitle',
            'section_title'     => 'sectionTitle',
            'label'             => 'label',
            'hint'              => 'hint',
            'value'             => 'value',
            'display_value'     => 'displayValue',
            'visible_to_seller' => 'visibleToSeller',
            'position'          => 'position',
            'files'             => fn (array $files) => $this->files = collect($files)->map(fn (object $file) => new FormSubmissionFileDto($file)),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'                => ['required', 'integer'],
            'form_field_id'     => ['present', 'nullable', 'integer'],
            'field_key'         => ['required', 'string'],
            'field_type'        => ['required', Rule::in(collect(FormFieldDtoTypeEnum::cases())->pluck('value'))],
            'page_title'        => ['present', 'nullable', 'string'],
            'section_title'     => ['present', 'nullable', 'string'],
            'label'             => ['required', 'string'],
            'hint'              => ['present', 'nullable', 'string'],
            'value'             => ['present'],
            'display_value'     => ['present', 'nullable', 'string'],
            'visible_to_seller' => ['required', 'boolean'],
            'position'          => ['required', 'integer'],
            'files'             => ['present', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSubmissionValueDtoNotConstructableException($msg, previous: $previous);
    }
}
