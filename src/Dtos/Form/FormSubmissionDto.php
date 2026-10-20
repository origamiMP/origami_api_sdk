<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

/**
 * A customer's answers to a form. A seller only gets the values flagged visible to sellers.
 */
class FormSubmissionDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public string $formName;

    /**
     * Default include of the API.
     *
     * @var Collection<int, FormSubmissionValueDto>
     */
    public Collection $values;

    public static function getAvailableIncludes(): array
    {
        return [
            'values' => FormSubmissionValueDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'        => 'id',
            'form_name' => 'formName',
            'values'    => fn (object $values) => $this->values = $this->collectIncluded($values, FormSubmissionValueDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'        => ['required', 'integer'],
            'form_name' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSubmissionDtoNotConstructableException($msg, previous: $previous);
    }
}
