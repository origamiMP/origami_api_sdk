<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * A customer's answers to a form. A seller only gets the values flagged visible to sellers.
 */
class FormSubmissionDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public int $userGroupId;

    public ?int $userId;

    public ?int $formId;

    public string $formName;

    public ?string $context;

    public ?int $languageId;

    public ?Carbon $submittedAt;

    public ?Carbon $lockedAt;

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
        $structure = [
            'id'            => 'id',
            'user_group_id' => 'userGroupId',
            'user_id'       => 'userId',
            'form_id'       => 'formId',
            'form_name'     => 'formName',
            'context'       => 'context',
            'language_id'   => 'languageId',
            'submitted_at'  => fn (?string $submittedAt) => $this->submittedAt = is_null($submittedAt) ? null : Carbon::parse($submittedAt),
            'locked_at'     => fn (?string $lockedAt) => $this->lockedAt = is_null($lockedAt) ? null : Carbon::parse($lockedAt),
            'values'        => fn (object $values) => $this->values = $this->collectIncluded($values, FormSubmissionValueDto::class),
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'            => ['required', 'integer'],
            'user_group_id' => ['required', 'integer'],
            'user_id'       => ['present', 'nullable', 'integer'],
            'form_id'       => ['present', 'nullable', 'integer'],
            'form_name'     => ['required', 'string'],
            'context'       => ['present', 'nullable', 'string'],
            'language_id'   => ['present', 'nullable', 'integer'],
            'submitted_at'  => ['present', 'nullable', 'date'],
            'locked_at'     => ['present', 'nullable', 'date'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormSubmissionDtoNotConstructableException($msg, previous: $previous);
    }
}
