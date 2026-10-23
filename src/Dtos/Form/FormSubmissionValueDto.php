<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionValueDtoNotConstructableException;

/**
 * One answer, with the labels of the form as they were when the customer answered.
 */
class FormSubmissionValueDto extends ApiResponseDto
{
    public int $id;

    public ?string $pageTitle;

    public string $label;

    /**
     * Addresses the files of the answer on the download route: value ids change on update.
     */
    public ?string $fieldKey = null;

    /**
     * Null for a file field: see $files.
     */
    public mixed $value;

    public ?string $displayValue;

    public int $position;

    /** @var Collection<int, FormSubmissionFileDto> */
    public Collection $files;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'            => 'id',
            'page_title'    => 'pageTitle',
            'label'         => 'label',
            'field_key'     => 'fieldKey',
            'value'         => 'value',
            'display_value' => 'displayValue',
            'position'      => 'position',
            'files'         => fn (array $files) => $this->files = collect($files)->map(fn (object $file) => new FormSubmissionFileDto($file)),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'            => ['required', 'integer'],
            'page_title'    => ['present', 'nullable', 'string'],
            'label'         => ['required', 'string'],
            'value'         => ['present'],
            'display_value' => ['present', 'nullable', 'string'],
            'position'      => ['required', 'integer'],
            'files'         => ['present', 'array'],
            'field_key'     => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSubmissionValueDtoNotConstructableException($msg, previous: $previous);
    }
}
