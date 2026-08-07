<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField\CustomFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * Represents a Custom Field definition, as returned by the `custom_fields` catalog
 * endpoint (e.g. `GET custom_fields?filter[model_type]=seller`).
 *
 * Distinct from CustomFieldModelValueDto, which represents a value already set on
 * an entity (Seller, User, ...) and comes from a different, nested JSON shape.
 */
class CustomFieldDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    public string $key;

    public CustomFieldDtoTypeEnum $type;

    public string $modelType;

    public ?int $customFieldGroupId;

    public mixed $params;

    public bool $isPrivate;

    /**
     * @var Collection|CustomFieldTranslationDto[]
     */
    public Collection $translations;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                    => 'id',
            'key'                   => 'key',
            'type'                  => fn ($type) => $this->type = CustomFieldDtoTypeEnum::tryFrom($type),
            'model_type'            => 'modelType',
            'custom_field_group_id' => 'customFieldGroupId',
            'params'                => 'params',
            'private'               => 'isPrivate',
            // Fractal wraps every include (even nested ones) in its own `{"data": [...]}` envelope.
            'translations' => fn ($translations) => $this->initTranslations($translations->data ?? []),
        ];

        return array_merge($structure, $this->getTimestampsAsDataStructureToProperties());
    }

    protected function validationRulesForProperties(): array
    {
        $types = collect(CustomFieldDtoTypeEnum::cases())->pluck('value');

        $rules = [
            'id'                    => ['required', 'integer'],
            'key'                   => ['required', 'string'],
            'type'                  => ['required', Rule::in($types)],
            'model_type'            => ['required', 'string'],
            'custom_field_group_id' => ['present', 'nullable', 'integer'],
            'params'                => ['present', 'nullable'],
            'private'               => ['required', 'boolean'],
            'translations'          => ['required', 'array'],
        ];

        return array_merge($rules, $this->getTimestampsValidationRules());
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initTranslations(array $translations): void
    {
        $this->translations = collect($translations)->map(fn ($translation) => new CustomFieldTranslationDto($translation));
    }
}
